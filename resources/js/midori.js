/*

   Midori JS Framework 2010.06 Copyright (c) 2008-2010 Aycan Gulez
   http://www.midorijs.com

   Permission is hereby granted, free of charge, to any person
   obtaining a copy of this software and associated documentation
   files (the "Software"), to deal in the Software without
   restriction, including without limitation the rights to use,
   copy, modify, merge, publish, distribute, sublicense, and/or sell
   copies of the Software, and to permit persons to whom the
   Software is furnished to do so, subject to the following
   conditions:

   The above copyright notice and this permission notice shall be
   included in all copies or substantial portions of the Software.

   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
   OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
   NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
   HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
   OTHER DEALINGS IN THE SOFTWARE.

*/


var midori =
{
   browserType: window.opera ? 'Opera' :
      ((navigator.userAgent.indexOf('WebKit') != -1) ? 'Safari' :
         ((navigator.userAgent.indexOf('MSIE') != -1) ? 'MSIE' : 'Gecko')),

   browserOS: (navigator.userAgent.indexOf('Windows') != -1) ? 'Win' :
      ((navigator.userAgent.indexOf('Macintosh') != -1) ? 'Mac' : 'Other'),

   domReady: [],


   each: function (parentObj, callBack, depthFirst)
   {
      var c = parentObj.firstChild;
      while (c)
         {
            if (!depthFirst)  callBack(c);
            if (c.firstChild) this.each(c, callBack, depthFirst);
            if (depthFirst)   callBack(c);
            c = c.nextSibling;
         }
   },


   sibling: function (obj, direction)
   {
      var sibling = obj;
      if (direction == 'next')
         do sibling = sibling.nextSibling;     while (sibling && sibling.nodeType == 3)
      else if (direction == 'prev')
         do sibling = sibling.previousSibling; while (sibling && sibling.nodeType == 3)
      return (sibling == obj) ? false : sibling;
   },


   parseSelectors: function (selectorText)
   {
      var c              = this.trim(selectorText).split('');
      var sI             = -1;
      var bracketContent = '';
      var elements   = [],    attrs      = [],    separators = [];
      var inSelector = false, inBrackets = false, inQuotes   = false;

      for (var i = 0, len = c.length; i < len; i++)
         if (inSelector)
            {
               if (inBrackets)
                  switch (c[i])
                     {
                        case '"'  : inQuotes = !inQuotes; break;
                        case ']'  : if (!inQuotes) { attrs[sI].push(bracketContent); inBrackets = false; bracketContent = ''; } break;
                        case '\\' : bracketContent += c[++i]; break;
                        default   : bracketContent += c[i];
                     }
               else
                  switch (c[i])
                     {
                        case '['  : inBrackets = true; break;
                        case ' '  :
                        case '>'  :
                        case ','  : inSelector = false; separators[sI] = c[i]; break;
                        case '\\' : elements[sI] += c[++i]; break;
                        default   : elements[sI] += c[i];
                     }
            }
         else
            switch (c[i])
               {
                  case ' ' :
                  case '>' :
                  case ',' : separators[sI] += c[i]; break;
                  default  : inSelector = true; elements[++sI] = c[i]; attrs[sI] = [];
               }

      return { elements: elements, attrs: attrs, separators: separators };
   },


   processAttrs: function (match, a, exprs)
   {
      for (var i = 0, numA = a.length, attr; i < numA; i++)
         {
            attr = (a[i] == 'class') ?
               (match.className ? match.className : null) :
               match.getAttribute(a[i]);

            switch (typeof exprs[i])
               {
                  case 'undefined' : if (attr == null)         return false; break;
                  case 'string'    : if (attr == exprs[i])     return false; break;
                  default          : if (!exprs[i].test(attr)) return false;
               }
         }
      return true;
   },


   processPseudo: function (match, pSelector, pA, pB)
   {
      var pCache, nodeKey, parentChildren = [], pI = 0;

      if (!(nodeKey = match.parentNode.getAttribute('midorinodekey')))
         match.parentNode.setAttribute('midorinodekey', nodeKey = Math.random().toString().substr(2));

      if (pCache = this.pCache[nodeKey])
         parentChildren = pCache['parentChildren'], pI = pCache['pI'];
      else
         {
            var c = match.parentNode.firstChild;
            while (c)
               {
                  if (c.nodeType == 1) parentChildren.push(c);
                  c = c.nextSibling;
               }
            this.pCache[nodeKey] = { parentChildren: parentChildren, pI: 0 };
         }
      var parentNumChildren = parentChildren.length;

      switch (pSelector)
         {
            case 'first-child' : if (match == parentChildren[0]) return true; break;
            case 'last-child'  : if (match == parentChildren[parentNumChildren - 1]) return true; break;
            case 'only-child'  : if (parentNumChildren == 1) return true; break;
         }

      if (pSelector == 'nth-child')
         {
            var v    = pA * pI + pB;
            var oldV = -50;
            while (v > -50 && v <= parentNumChildren)
               {
                  if (v >= 0 && parentChildren[v - 1] == match)
                     {
                        this.pCache[nodeKey]['pI'] = (pA >= 0) ? pI + 1 : 0;
                        return true;
                     }
                  pI++, v += pA;
                  if (v == oldV) break;
                  oldV = v;
               }
         }
   },


   getMatches: function (target, s, a, oneLevelOnly)
   {
      this.pCache = {};
      var matches = [], exprs = [];
      var chunks, objs, numObjs, pseudo, pSelector, pOption, pA, pB;


      this.postProcess = function (me)
      {
         if (!numA && !pseudo)
            {
               matches.push(me);
               return;
            }
         var match = true;
         if (numA   && !this.processAttrs(me, a, exprs))           match = false;
         if (pseudo && !this.processPseudo(me, pSelector, pA, pB)) match = false;
         if (match) matches.push(me);
      }


      for (var i = 0, numA = a.length; i < numA; i++)
         {
            chunks = a[i].match(/([a-z0-9_-]+)\s*([=^$*|~!]{0,2})\s*"?([^"]*)"?$/i);
            a[i]   = chunks[1];
            switch (chunks[2])
               {
                  case  '=' : exprs[i] = new RegExp('^' + chunks[3] + '$', 'i'); break;
                  case '^=' : exprs[i] = new RegExp('^' + chunks[3], 'i');       break;
                  case '$=' : exprs[i] = new RegExp(chunks[3] + '$', 'i');       break;
                  case '*=' : exprs[i] = new RegExp(chunks[3], 'i');             break;
                  case '~=' : exprs[i] = new RegExp('^' + chunks[3] + '$|^' + chunks[3] + '\\s|\\s' + chunks[3] + '\\s|\\s' + chunks[3] + '$', 'i'); break;
                  case '!=' : exprs[i] = chunks[3];
               }
         }

      if (s.indexOf(':') != -1)
         {
            chunks    = s.split(':');
            s         = chunks[0];
            pseudo    = chunks[1].match(/([a-z-]+)\(?([a-z0-9+-]*)\)?/i);
            pSelector = pseudo[1].toLowerCase();

            switch (pOption = pseudo[2].toLowerCase())
               {
                  case 'odd'  : pOption = '2n+1'; break;
                  case 'even' : pOption = '2n';
               }

            chunks = pOption.match(/([0-9+-]*)(n?)([0-9+-]*)/i);
            pA     = parseInt(chunks[2] ? (chunks[1] ? ((chunks[1] == '-') ? -1 : chunks[1]) : 1) : 0);
            pB     = parseInt(chunks[3] ? chunks[3] : ((chunks[1] && !chunks[2]) ? chunks[1] : 0));
         }

      if (s.indexOf('#') != -1)
         this.postProcess(document.getElementById(s.substr(s.indexOf('#') + 1)));

      else if (s.indexOf('.') != -1)
         {
            chunks         = s.split('.');
            var classMatch = s.substr(chunks[0].length + 1).replace('.', ' ');
            var className  = new RegExp('^' + classMatch + '$|^' + classMatch + '\\s|\\s' + classMatch + '\\s|\\s' + classMatch + '$', 'i');
            objs           = target.getElementsByTagName(chunks[0] ? chunks[0] : '*');
            for (i = 0, numObjs = objs.length; i < numObjs; i++)
               if ((!oneLevelOnly && className.test(objs[i].className)) ||
                   (oneLevelOnly && className.test(objs[i].className) && objs[i].parentNode == target))
                  this.postProcess(objs[i]);
         }

      else if (s == '*' || /^[A-Za-z0-9]+$/.test(s))
         for (i = 0, objs = target.getElementsByTagName(s), numObjs = objs.length; i < numObjs; i++)
            if (!oneLevelOnly || (oneLevelOnly && objs[i].parentNode == target))
               this.postProcess(objs[i]);

      return matches;
   },


   get: function (selectorText, startAt)
   {
      var selectors = this.parseSelectors(selectorText);
      var numS      = selectors['elements'].length;

      if (!startAt)
         startAt = document;

      if (numS == 1)
         {
            var idMatch = selectors['elements'][0].match(/^[a-z0-9*]*#([^,:]+)$/i);
            if (idMatch && selectors['attrs'][0] == '' && selectors['separators'] == '')
               return document.getElementById(idMatch[1]);
         }

      var objs    = this.getMatches(startAt, selectors['elements'][0], selectors['attrs'][0]);
      var allObjs = [], newObjs, separator;
      for (var i = 1; i < numS; i++)
         {
            newObjs   = [];
            separator = this.trim(selectors['separators'][i - 1]);
            if (separator == ',')
               {
                  allObjs = this.concatUnique(allObjs, objs);
                  objs    = this.getMatches(startAt, selectors['elements'][i], selectors['attrs'][i]);
               }
            else
               {
                  var oneLevelOnly = (separator == '>') ? true : false;
                  for (var j = 0, numObjs = objs.length; j < numObjs; j++)
                     if (!this.inArray(objs[j], newObjs))
                        newObjs = this.concatUnique(newObjs, this.getMatches(objs[j], selectors['elements'][i], selectors['attrs'][i]), oneLevelOnly);
                  objs = newObjs;
               }
         }
      allObjs       = this.concatUnique(allObjs, objs);
      allObjs.apply = function (p) {
         for (var i = 0, numObjs = this.length; i < numObjs; i++) (typeof p == 'function') ? p(this[i]) : eval('this[i].' + p) };

      return allObjs;
   },


   getCssRule: function (stylesheet, rule, property)
   {
      var values = [];
      var rules  = document.styleSheets[stylesheet];
      rules      = rules.rules ? rules.rules : rules.cssRules;
      rule       = rule.toLowerCase();
      property   = (this.browserType == 'Safari') ? property.replace(/([A-Z])/, '-$1').toLowerCase() : property.toLowerCase();
      for (var i = 0, numRules = rules.length; i < numRules; i++)
         if (rule == '*' || rules[i].selectorText.toLowerCase() == rule)
            for (var j in rules[i].style)
               if (this.browserType == 'Safari')
                  {
                     if (rules[i].style[j].toLowerCase && rules[i].style[j].toLowerCase() == property)
                        { if (rule == '*') values[rules[i].selectorText] = value; else return rules[i].style[rules[i].style[j]]; }
                  }
               else if (j.toLowerCase() == property)
                  { if (rule == '*') values[rules[i].selectorText] = rules[i].style[j]; else return rules[i].style[j]; }
      return values;
   },


   setStyles: function (obj, styleList)
   {
      for (var i in styleList)
         (i == 'float') ? this.setFloat(obj, styleList[i]) : obj.style[i] = styleList[i];
   },


   setAttributes: function (obj, attrList)
   {
      for (var i in attrList)
         (i == 'className') ? obj.className = attrList[i] : obj.setAttribute(i, attrList[i]);
   },


   removeNode: function(obj)
   {
      return obj.parentNode.removeChild(obj);
   },


   addEventListener: function (target, eventType, listenerFunc)
   {
      this.safariReady = function ()
      {
         this.readyTimer = setInterval(function() { if (/loaded|complete/.test(document.readyState)) midori.runReadyEvents() }, 10);
      }

      this.msieReady = function()
      {
         document.write('<script id="midori_onload" src="javascript: {}" defer="true"></script>');
         this.get('#midori_onload').onreadystatechange = function() { if (this.readyState == 'complete') midori.runReadyEvents() };
      }


      if (target.addEventListener)
         {
            if (eventType == 'ready')
               switch (this.browserType)
                  {
                     case 'Safari' : this.domReady.push(listenerFunc); if (!this.readyTimer) this.safariReady(); return;
                     case 'Opera'  :
                     case 'Gecko'  : eventType = 'DOMContentLoaded'; break;
                     default       : eventType = 'load';
                  }
            target.addEventListener(eventType, listenerFunc, false);
            return;
         }

      if (eventType == 'ready') // MSIE
         {
            if (!this.domReady.length) this.msieReady();
            this.domReady.push(listenerFunc);
            return;
         }
      target.attachEvent('on' + eventType, listenerFunc);
   },


   runReadyEvents: function ()
   {
      if (this.readyTimer) clearInterval(this.readyTimer);
      for (var i = 0, numE = this.domReady.length; i < numE; i++) this.domReady[i]();
   },


   getEventTarget: function (event)
   {
      var target = event.target ? event.target : event.srcElement;
      if (target.nodeType == 3) target = target.parentNode;
      return target;
   },


   getMousePos: function (event)
   {
      if (event.targetTouches && event.targetTouches.length)
         return { x: event.targetTouches[0].pageX, y: event.targetTouches[0].pageY };
      else if (event.pageX || event.pageY)
         return { x: event.pageX, y: event.pageY };
      else
         return { x: event.clientX + document.documentElement.scrollLeft - document.body.clientLeft,
                  y: event.clientY + document.documentElement.scrollTop  - document.body.clientTop };
   },


   preventBubble: function (event)
   {
      event.stopPropagation ? event.stopPropagation() : window.event.cancelBubble = true;
   },


   preventDefault: function (event)
   {
      event.preventDefault ? event.preventDefault() : window.event.returnValue = false;
   },


   getFloat: function (o)
   {
      return ((this.browserType == 'MSIE') ? o.style.styleFloat : o.style.cssFloat);
   },


   setFloat: function (o, v)
   {
      (this.browserType == 'MSIE') ? o.style.styleFloat = v : o.style.cssFloat = v;
   },


   getSelection: function (target)
   {
      if (this.browserType != 'MSIE') return target.getSelection();

      if (target == window) target = document;
      var cursorPos = target.selection.createRange();
      if (target.selection.type != 'Control')
         return cursorPos;
   },


   getSelectionText: function (cursorPos)
   {
      return (this.browserType == 'MSIE') ? cursorPos.htmlText : cursorPos.toString();
   },


   getCookie: function (cookieName)
   {
      var cookies = document.cookie.split('; ');
      for (var i = 0, numCookies = cookies.length; i < numCookies; i++)
         {
            var parts = cookies[i].split('=');
            if (parts[0] == cookieName)
               return unescape(parts[1].replace(/\+/g, ' '));
         }
   },


   setCookie: function (name, value, expires, path, domain)
   {
      var today = new Date();
      document.cookie = name + '=' + escape(value) + '; expires=' + today.toUTCString(today.setSeconds(expires)) +
         (path ? '; path=' + path : '') + (domain ? '; domain=' + domain : '');
   },


   convertToFields: function (parentNode, prefix, a)
   {
      for (var k in a)
         if (typeof a[k] == 'object')
            this.convertToFields(parentNode, prefix + '[' + k + ']', a[k]);
         else
            {
               var el = document.createElement('input');
               this.setAttributes(el, { type: 'hidden', name: prefix + '[' + k + ']', value: a[k] } );
               parentNode.appendChild(el);
            }
   },


   replace: function (st, params)
   {
      if (!params) return st;
      var matches = st.match(/:[A-Za-z0-9_]+/g).sort().reverse();
      for (var i = 0, numMatches = matches.length; i < numMatches; i++)
         st = st.replace(matches[i], params[matches[i].substr(1)]);

      return st;
   },


   trim: function (st)
   {
      return st.replace(/^\s+|\s+$/g, '');
   },


   uniqid: function (range)
   {
      return Math.floor(Math.random() * (range ? range : 100000));
   },


   concatUnique: function (a1, a2)
   {
      var uniqA2 = [];
      for (var i = 0, numA2 = a2.length; i < numA2; i++)
         if (!this.inArray(a2[i], a1))
            uniqA2.push(a2[i]);

      return a1.concat(uniqA2);
   },


   implode: function (glue, a)
   {
      if (typeof a != 'object')
         return a;

      var o = '';
      if (a.length && !a.propertyIsEnumerable('length'))
         for (var i = 0, numA = a.length; i < numA; i++)
            o += glue + a[i];
      else
         for (var k in a)
            o += glue + a[k];

      return o.substr(glue.length);
   },


   inArray: function (v, a)
   {
      for (var i = 0, numA = a.length; i < numA; i++)
         if ((v != null && a[i].constructor == Array && v.constructor == Array && a[i].toString() == v.toString()) || (a[i] == v)) return true;
   },


   shortenWords: function (obj, maxLen)
   {
      maxLen = maxLen ? maxLen - 3: 45;
      this.each(obj, function (o) {
         if (o.nodeType != 3)
            return;
         var chunks    = o.data.split(' ');
         var shortened = false, stlen;
         for (var i = 0, numChunks = chunks.length; i < numChunks; i++)
            if ((stLen = chunks[i].length) > maxLen)
               {
                  var diffLen  = Math.floor(stLen - maxLen);
                  var startPos = Math.floor(stLen / 2 - diffLen / 2);
                  chunks[i]    = chunks[i].substr(0, startPos) + '...' + chunks[i].substr(startPos + diffLen);
                  shortened    = true;
               }
         if (shortened)
            o.data = midori.implode(' ', chunks);
      } );
   },


   resizeImg: function (obj, maxWidth)
   {
      maxWidth = maxWidth ? maxWidth : 400;
      this.get('img', obj).apply(function (o) {
         if (o.width > maxWidth)
            {
               if (o.style.msInterpolationMode) o.style.msInterpolationMode = 'bicubic';
               o.width = maxWidth;
            }
      } );
   },


   saveCheckboxState: function (element, cb, callback)
   {
      cb.checked ?
         element.innerHTML++ :
         (--element.innerHTML == 0) ? element.innerHTML = '' : {};

      if (callback) callback(element.innerHTML);
   },


   checkRequiredFields: function (vars)
   // Accepted params: event, formId, required, message, callback
   {
      vars.required  = vars.required.split(',');
      var form       = this.get('#' + vars.formId);
      var stopSubmit = false;
      var fieldName, field, fieldType, fieldStyle;

      for (var i = 0, numFields = vars.required.length; i < numFields; i++)
         if (fieldName = this.trim(vars.required[i]))
            {
               field      = this.get('#' + fieldName);
               fieldType  = (field.type.toLowerCase() == 'checkbox') ? 'c' : 't';
               fieldStyle = (fieldType == 'c') ? field.parentNode.style : field.style;
               fieldStyle.backgroundColor = '';
               if ((fieldType == 'c' && !field.checked) || (this.trim(field.value) == ''))
                  {
                     fieldStyle.backgroundColor = '#FAA';
                     stopSubmit = true;
                  }
            }

      this.get('#' + vars.formId + '-status').innerHTML = stopSubmit ? vars.message : '';

      var callbackResult = vars.callback ? vars.callback() : '';
      if (callbackResult === false || stopSubmit) // the order is important
         {
            if (vars.event) this.preventDefault(vars.event);
            return false;
         }
      else if (!vars.event)
         form.submit();
   },


   getWindowDims: function ()
   {
      if (this.browserType == 'MSIE')
         return { windowWidth: document.documentElement.clientWidth, windowHeight: document.documentElement.clientHeight,
                  scrollTop:   document.documentElement.scrollTop };
      else
         return { windowWidth: window.innerWidth, windowHeight: window.innerHeight, scrollTop: window.scrollY };
   },


   getPos: function (obj, stopAt)
   {
      var xPos = 0, yPos = 0;
      stopAt = stopAt ? stopAt.offsetParent : null;
      while (obj.offsetParent != stopAt)
         xPos += obj.offsetLeft, yPos += obj.offsetTop - obj.scrollTop, obj = obj.offsetParent;

      return { x: xPos, y: yPos };
   },


   highlightRow: function (obj, highlightClass, removeAll)
   {
      this.get('td', obj).apply(function (o) {
         var className = o.className.split(' ');
         o.className = (className[className.length - 1] == highlightClass) ?
            o.className.substr(0, o.className.length - highlightClass.length - 1) :
            removeAll ? o.className : o.className + ' ' + highlightClass;
      } );
   }
}




var midoriFX =
{
   intervals:      {},
   lastIntervalId: 0,


   getOutsideSize: function (element, what)
   {
      var offsetSize      = what == 'width' ? element.offsetWidth : element.offsetHeight;
      element.style[what] = offsetSize.toString() + 'px';
      var outsideSize     = (what == 'width' ? element.offsetWidth : element.offsetHeight) - offsetSize;
      element.style[what] = (offsetSize - outsideSize).toString() + 'px';

      return outsideSize;
   },


   showWithAnim: function (vars)
   {
      var firstRun = this.intervals[vars.intervalKey].newSize ? false : true;
      this.intervals[vars.intervalKey].newSize += Math.round((vars.elementSize - this.intervals[vars.intervalKey].newSize) / 2);

      if (this.intervals[vars.intervalKey].newSize < vars.elementSize)
         vars.element.style[vars.what] = this.intervals[vars.intervalKey].newSize.toString() + 'px';
      else
         {
            clearInterval(this.intervals[vars.intervalKey].intervalId);
            vars.element.style[vars.what] = vars.elementSize.toString() + 'px';
            vars.element.style.overflow   = 'visible';
            if (vars.callback) vars.callback(vars.element);
         }

      if (firstRun) vars.element.style.display = 'block';
   },


   show: function (id, callback, horiz)
   {
      var what    = horiz ? 'width' : 'height';
      var element = midori.get('#' + id);
      var vars    = { intervalKey: Math.random(), element: element, callback: callback, what: what };

      midori.setStyles(element, { overflow: 'hidden', visibility: 'hidden', display: 'block' } );
      vars.elementSize = (horiz ? element.offsetWidth : element.offsetHeight) - this.getOutsideSize(element, what),
      midori.setStyles(element, { display:  'none',   visibility: 'visible' } );

      this.intervals[vars.intervalKey] = { newSize: 0, intervalId: setInterval(function () { midoriFX.showWithAnim(vars) }, 40) };
   },


   hideWithAnim: function (vars)
   {
      var oldSize = (vars.what == 'width' ? vars.element.offsetWidth : vars.element.offsetHeight) - vars.outsideSize;
      var newSize = vars.constantSpeed ? vars.oldSize - 4 : Math.round(oldSize / 1.5);

      if (newSize > 2)
         {
            vars.element.style[vars.what] = newSize.toString() + 'px';
            vars.element.style.opacity    = newSize / 50;
         }
      else
         {
            clearInterval(this.intervals[vars.intervalKey].intervalId);
            vars.element.style.display    = 'none';
            vars.element.style[vars.what] = vars.elementSize.toString() + 'px';
            vars.element.style.opacity    = 1;
            if (vars.callback) vars.callback(vars.element);
         }
   },


   hide: function (id, callback, constantSpeed, horiz)
   {
      var what        = horiz ? 'width' : 'height';
      var element     = midori.get('#' + id);
      var outsideSize = this.getOutsideSize(element, what);
      var vars        = {
         intervalKey: Math.random(), element: element, outsideSize: outsideSize,
         elementSize: (horiz ? element.offsetWidth : element.offsetHeight) - outsideSize,
         callback: callback, constantSpeed: constantSpeed, what: what };
      element.style.overflow = 'hidden';
      this.intervals[vars.intervalKey] = { intervalId: setInterval(function () { midoriFX.hideWithAnim(vars) }, 40) };
   },


   scrollToWithAnim: function (intervalKey, targetPos)
   {
      if (Math.abs(targetPos - this.intervals[intervalKey].scrollPos) > 10)
         {
            this.intervals[intervalKey].scrollPos += Math.round((targetPos - this.intervals[intervalKey].scrollPos) / 8);
            scrollTo(0, this.intervals[intervalKey].scrollPos);
         }
      else
         clearInterval(this.intervals[intervalKey].intervalId);
   },


   scrollTo: function (id, correction)
   {
      var targetPos   = midori.getPos(midori.get('#' + id)).y + (correction ? correction : 0);
      var intervalKey = Math.random();
      var dims        = midori.getWindowDims();
      this.intervals[intervalKey] =
         { scrollPos: dims.scrollTop, intervalId: setInterval(function () { midoriFX.scrollToWithAnim(intervalKey, targetPos) }, 15) };
   },


   sliderToWithAnim: function (intervalKey, container, targetPos)
   {
      var data        = this.intervals[intervalKey];
      var diff        = Math.abs(targetPos - data.targetPos);
      data.targetPos += Math.round(diff / 8) * data.direction;
      container.style.marginLeft = -data.targetPos + 'px';
      if (diff < 4)
         clearInterval(data.intervalId);
   },


   slider: function (containerId, targetId, correction)
   {
      var container   = midori.get('#' + containerId);
      var targetPos   = midori.getPos(midori.get('#' + targetId), container).x;
      var currentPos  = container.style.marginLeft ? Math.abs(parseInt(container.style.marginLeft, 10)) : 0;
      var intervalKey = Math.random();
      if (this.lastIntervalId) clearInterval(this.lastIntervalId);
      this.intervals[intervalKey] = {
         targetPos:  currentPos,
         direction:  (currentPos > targetPos) ? -1 : 1,
         intervalId: setInterval(function () { midoriFX.sliderToWithAnim(intervalKey, container, targetPos) }, 15) };
      this.lastIntervalId = this.intervals[intervalKey].intervalId;
   }
}




var midoriPopup =
{
   show: function (vars)
   // Accepted params: event, obj, popupId, showAtMousePos, showCallback, hideCallback, x, y, noAnim
   {
      if (typeof vars.x == 'undefined') vars.x =  5;
      if (typeof vars.y == 'undefined') vars.y = -5;

      this.popupId = vars.popupId;
      var popup    = midori.get('#' + vars.popupId);
      var dims     = midori.getWindowDims();
      var popupPos = vars.showAtMousePos ? midori.getMousePos(vars.event) : midori.getPos(vars.obj);

      popup.style.display = 'block';
      if (this.activePopup) this.activePopup.style.display = 'none';
      vars.obj.blur();

      if (dims.windowWidth < popupPos.x + popup.offsetWidth + vars.x)
         popupPos.x -= popup.offsetWidth;

      while (popupPos.y + popup.offsetHeight + vars.y - dims.scrollTop > dims.windowHeight)
         popupPos.y -= popup.offsetHeight + 20;

      midori.setStyles(popup, { left: (popupPos.x + vars.x) + 'px', top: (popupPos.y + vars.y + vars.obj.offsetHeight) + 'px' } );
      this.activePopup = popup;

      vars.noAnim ? popup.style.display = 'block' : midoriFX.show(this.popupId);

      if (vars.event)        midori.preventDefault(vars.event);
      if (vars.showCallback) vars.showCallback(this);

      this.hideCallback = vars.hideCallback ? vars.hideCallback : false;
   },


   hide: function ()
   {
      if (this.activePopup == null) return;
      if (this.hideCallback)        this.hideCallback(this);

      midoriFX.hide(this.popupId);
      this.activePopup = null;
   }
}

midori.addEventListener(document, 'mouseup',  function (e) { midoriPopup.hide() } );
midori.addEventListener(document, 'touchend', function (e) { midoriPopup.hide() } );




var midoriTab =
{
   selectedTabs: {},


   select: function (obj, noAnim)
   {
      var tabSet = obj.getAttribute('tabset');
      if (this.selectedTabs[tabSet])
         {
            this.selectedTabs[tabSet].parentNode.className = '';
            midori.get('#' + this.selectedTabs[tabSet].hash.substr(1)).style.display = 'none';
         }

      this.selectedTabs[tabSet] = obj;
      obj.parentNode.className  = 'tab-selected';
      noAnim ? midori.get('#' + obj.hash.substr(1)).style.display = 'block' : midoriFX.show(obj.hash.substr(1));
   },


   init: function ()
   {
      midori.get('.tab-set').apply(function(obj) {
         midori.get('#' + obj.id + ' a').apply(function (o) {
            o.setAttribute('tabset', obj.id);
            if (o.parentNode.className == 'tab-selected')
               {
                  midoriTab.selectedTabs[obj.id] = o;
                  midoriTab.select(o, true);
               }
            midori.addEventListener(o, 'click', function (e) {
               var me = midori.getEventTarget(e);
               me.blur();
               midoriTab.select(me);
               midori.preventDefault(e);
            } );
         } );
      } );
   }
}




var midoriHistory =
{
   history: [],


   modifyLocation: function (item)
   {
      var loc = window.location.toString();
      window.location = (loc.indexOf('#') == -1) ? loc + '#' + item : loc.replace(/#.+/, '#' + item);
   },


   add: function (item)
   {
      if (item == this.last)
         return;
      this.history.push(item);
      this.modifyLocation(item);
      this.last = item;

      if (midori.browserType == 'MSIE')
         {
            var iframe = midori.get('#midori_history').contentWindow.document;
            iframe.open('javascript: "<html></html>"');
            iframe.write('<html><body><div id="me">' + item + '</div></body></html>');
            iframe.close();
         }
   },


   remove: function (item)
   {
      var history = this.history;
      for (var i = 0, len = history.length; i < len; i++)
         if (history[i] == item)
            {
               history.splice(i, 1);
               if (i == len) this.last = history[history.length - 1];
            }
      this.history = history;
   },


   onChange: function ()
   {
      var newLoc = window.location.toString();
      var item   = (midori.browserType == 'MSIE') ?
         midori.get('#midori_history').contentWindow.document.getElementById('me').innerText :
         (newLoc.indexOf('#') != -1) ? newLoc.match(/#(.+)$/)[1] : '';

      if (midori.browserType == 'MSIE')
         {
            if (midoriHistory.oldItem != item && midori.inArray(item, midoriHistory.history))
               {
                  midoriHistory.oldItem = item;
                  midoriHistory.modifyLocation(item);
                  midoriHistory.callback(item);
               }
         }
      else if (midoriHistory.oldLoc != newLoc && midori.inArray(item, midoriHistory.history))
         {
            midoriHistory.oldLoc = newLoc;
            midoriHistory.callback(item);
         }
   },


   init: function (callback)
   {
      this.callback = callback;
      if (midori.browserType == 'MSIE')
         document.body.appendChild(document.createElement('div')).innerHTML =
            '<iframe id="midori_history" style="position: absolute; width: 1px; height: 1px"></iframe>';
      setInterval(this.onChange, 250);
   }
}




function midoriTableSelection(vars)
// Accepted params: tableId, rowPrefix, checkboxName, rowHighlight, showCallback, hideCallback
{
   this.vars   = vars;
   this.rowIds = [];
   var thisObj = this;

   var cb = document.createElement('input');
   cb.setAttribute('type', 'checkbox');
   midori.addEventListener(cb, 'click', function (e) {
      var id, el, isChecked;
      for (var i = 0, numIds = thisObj.rowIds.length; i < numIds; i++)
         {
            id         = thisObj.rowIds[i];
            el         = midori.get('#' + vars.rowPrefix + 'cb_' + id).firstChild;
            isChecked  = el.checked;
            el.checked = !isChecked;
            el.value   = isChecked ? '' : id;
            midori.highlightRow(midori.get('#' + vars.rowPrefix + id), vars.rowHighlight);
            midori.saveCheckboxState(midori.get('#' + vars.tableId + '_cb_parent'), el);
         }
   } );

   var firstTh = midori.get('#' + vars.tableId + ' th')[0];
   var th      = document.createElement('th');
   midori.setAttributes(th, { id: vars.rowPrefix + 'header-cb', align: 'left', className: firstTh.className } );
   th.appendChild(cb);
   th.style.display = 'none';
   firstTh.parentNode.appendChild(th);

   var cbParent = document.createElement('div');
   cbParent.id  = vars.tableId + '_cb_parent';
   document.body.appendChild(cbParent);

   midori.get('#' + vars.tableId + ' tr[id^="' + vars.rowPrefix + '"]').apply(function (o) {
      var td, el, id, className;
      midori.get('td:last-child', o).apply(function (c) { className = c.className } );

      id = o.id.substr(vars.rowPrefix.length);
      td = document.createElement('td');
      td.style.display = 'none';
      midori.setAttributes(td, { id: vars.rowPrefix + 'cb_' + id, className: className } );

      el = document.createElement('input');
      midori.setAttributes(el, { name: vars.checkboxName, type: 'checkbox', value: id } );
      midori.addEventListener(el, 'click', function (e) {
         midori.highlightRow(midori.get('#' + vars.rowPrefix + id), vars.rowHighlight);
         midori.saveCheckboxState(midori.get('#' + vars.tableId + '_cb_parent'), el);
         this.value = id;
      } );
      td.appendChild(el);
      o.appendChild(td);
      thisObj.rowIds.push(id);
   } );


   this.toggle = function()
   {
      var numRowIds = this.rowIds.length;
      if (midori.get('#' + this.vars.rowPrefix + 'header-cb').style.display == 'none')
         {
            midori.get('#' + this.vars.rowPrefix + 'header-cb').style.display = '';
            for (var i = 0; i < numRowIds; i++)
               {
                  var rowId = this.rowIds[i];
                  midori.get('#' + this.vars.rowPrefix + 'cb_' + rowId).style.display = '';
                  if (midori.get('#' + this.vars.rowPrefix + 'cb_' + rowId).firstChild.checked)
                     midori.highlightRow(midori.get('#' + this.vars.rowPrefix + rowId), this.vars.rowHighlight);
               }
            if (vars.showCallback) vars.showCallback(this);
         }
      else
         {
            midori.get('#' + this.vars.rowPrefix + 'header-cb').style.display = 'none';
            for (var i = 0; i < numRowIds; i++)
               {
                  midori.get('#' + this.vars.rowPrefix + 'cb_' + this.rowIds[i]).style.display = 'none';
                  midori.highlightRow(midori.get('#' + this.vars.rowPrefix + this.rowIds[i]), this.vars.rowHighlight, true);
               }
            if (vars.hideCallback) vars.hideCallback(this);
         }
   }
}




function midoriDragDrop(containerId, dropCallback)
{
   var thisObj    = this;
   this.container = midori.get('#' + containerId);


   this.init = function ()
   {
      this.objs       = [];
      this.objsCoords = [];
      this.mouseMoved = false;
      this.dragged    = null;

      midori.each(this.container, function (o) {
         if (/not-draggable/.test(o.className) || !/draggable/.test(o.className) || o.style.display == 'none')
            return;
         thisObj.objs.push(o);
         for (var i = 0; i < 2; i++)
            midori.addEventListener(o, ['mousedown', 'touchstart'][i], function (e) {
               var mousePos = midori.getMousePos(e);
               var me       = midori.getEventTarget(e);
               if (/not-draggable/.test(me.className))
                  return;

               while (!/draggable/.test(me.className))
                  me = me.parentNode;
               var objPos          = midori.getPos(me);
               thisObj.dragged     = me;
               thisObj.mouseOffset = { x: mousePos.x - objPos.x, y: mousePos.y - objPos.y };

               if (!/drop-target/.test(o.className))
                  {
                     me.style.opacity = '.5';
                     midori.preventBubble(e);
                     midori.preventDefault(e);
                  }

               thisObj.removeDraggedObj(me);
            } );
      }, true);
   }


   this.findPlace = function (event, mouseUp)
   {
      var mousePos = mouseUp && event.targetTouches ? this.lastMousePos : midori.getMousePos(event);
      var obj, objCoords, objPos;

      if (!this.objsCoords.length)
         for (var i = 0, numObjs = this.objs.length; i < numObjs; i++)
            if ((obj = this.objs[i]) && (objPos = midori.getPos(obj)))
               this.objsCoords.push( { obj: obj, x: objPos.x, y: objPos.y, width: obj.offsetWidth, height: obj.offsetHeight } );

      for (var j = 0, numObjsCoords = this.objsCoords.length; j < numObjsCoords; j++)
         if (objCoords = this.objsCoords[j])
            {
               if (!((mousePos.x >= objCoords.x && mousePos.x <= objCoords.x + objCoords.width) &&
                     (mousePos.y >= objCoords.y && mousePos.y <= objCoords.y + objCoords.height)))
                  continue;

               objCoords.where = midori.getFloat(objCoords.obj) ?
                  (mousePos.x < objCoords.x + objCoords.width  / 2) ? 'prev' : 'next' :
                  (mousePos.y < objCoords.y + objCoords.height / 2) ? 'prev' : 'next';

               return objCoords;
            }
   }


   this.removeDraggedObj = function (parentObj)
   {
      var j, numObjs = this.objs.length;
      midori.each(parentObj, function (o) {
         if (!/draggable/.test(o.className))
            return;
         for (j = 0; j < numObjs; j++)
            if (thisObj.objs[j] == o) { thisObj.objs[j] = ''; break; }
      }, true);

      for (j = 0; j < numObjs; j++)
         if (this.objs[j] == parentObj)  { this.objs[j] = ''; break; }
   }


   this.mouseMove = function (event)
   {
      if (!this.dragged || /drop-target/.test(this.dragged.className))
         return;
      midori.preventDefault(event);

      var mousePos      = midori.getMousePos(event);
      this.lastMousePos = mousePos;
      this.mouseMoved   = true;
      midori.setStyles(this.dragged, { position: 'absolute', left: (mousePos.x - this.mouseOffset.x) + 'px', top: (mousePos.y - this.mouseOffset.y) + 'px' } );
      midori.setFloat(this.spacer, midori.getFloat(this.dragged));

      var objCoords;
      if ((objCoords = this.findPlace(event)) && (this.dropCallback(objCoords, this.dragged, this.spacer)))
         {
            midori.setStyles(this.spacer, { display: 'block', height: this.dragged.offsetHeight + 'px' } );
            if (midori.getFloat(this.spacer))
               this.spacer.style.width = this.dragged.offsetWidth + 'px';
         }
      else
         this.spacer.style.display = 'none';
   }


   this.mouseUp = function (event)
   {
      this.doneDragging = false;
      if (!this.dragged)
         return;

      var objCoords;
      if (this.mouseMoved && (objCoords = this.findPlace(event, true)))
         {
            this.dropCallback(objCoords, this.dragged);
            this.doneDragging = true;
         }

      if (!this.dragged)
         return;

      this.spacer.style.display = 'none';
      midori.setStyles(this.dragged, { position: '', opacity: '1' } );
      this.init(this.container);
   }


   this.defaultDropCallback = function (o, dragged, spacer)
   {
      if (/drop-target/.test(o.obj.className))
         return o.obj.appendChild(spacer ? spacer : dragged);

      return (o.where == 'next' && !o.obj.nextSibling) ?
         o.obj.parentNode.appendChild( spacer ? spacer : dragged) :
         o.obj.parentNode.insertBefore(spacer ? spacer : dragged, (o.where == 'prev') ? o.obj : o.obj.nextSibling);
   }


   midori.addEventListener(this.container, 'mousemove', function (e) { thisObj.mouseMove(e) } );
   midori.addEventListener(this.container, 'mouseup',   function (e) { thisObj.mouseUp(e) } );
   midori.addEventListener(this.container, 'click',     function (e) { if (thisObj.doneDragging) midori.preventDefault(e) } );

   midori.addEventListener(this.container, 'touchmove', function (e) { thisObj.mouseMove(e) } );
   midori.addEventListener(this.container, 'touchend',  function (e) { thisObj.mouseUp(e) } );

   this.spacer           = document.createElement('div');
   this.spacer.innerHTML = '&nbsp;';
   midori.setAttributes(this.spacer, { id: 'midori_dd_spacer' + midori.uniqid(), className: 'midori-dd-spacer' } );

   this.dropCallback = dropCallback ? dropCallback : this.defaultDropCallback;
   this.init();
}




function midoriAjax(callback, params, cache)
{
   var thisObj   = this;
   this.cache    = {};
   this.callback = callback;


   try { this.request = new XMLHttpRequest() }
   catch (e)
      { try { this.request = new ActiveXObject('Msxml2.XMLHTTP') }
        catch (e)
           { this.request = new ActiveXObject('Microsoft.XMLHTTP') }
      }


   this.runCallback = function (event, cached)
   {
      if (!cached)
         {
            if (thisObj.request.readyState != 4)
               return;
            thisObj.responseText            = thisObj.request.responseText;
            thisObj.responseXML             = thisObj.request.responseXML;
            thisObj.status                  = thisObj.request.status;
            thisObj.cache[thisObj.cacheKey] = thisObj.responseText;
         }

      thisObj.callback(params);
   }


   this.post = function (where, what, verb, headers)
   {
      var cachedValue;
      this.cacheKey = where + '?' + what;
      if (cache && ((cachedValue = this.cache[this.cacheKey]) != null))
         {
            this.responseText = cachedValue;
            this.runCallback('', true);
            return;
         }

      this.request.onreadystatechange = this.runCallback;

      verb = verb ? midori.trim(((verb == true) ? 'GET' : verb).toUpperCase()) : 'POST';
      this.request.open(verb, midori.inArray(verb, ['POST', 'PUT']) ? where : where + (what ? '?' + what : ''), true);

      if (midori.inArray(verb, ['POST', 'PUT']))
         {
            this.request.setRequestHeader('Content-Type',   'application/x-www-form-urlencoded; charset=utf-8');
            this.request.setRequestHeader('Content-length', what.length);
            this.request.setRequestHeader('Connection',     'close');
         }
      if (headers)
         for (var i = 0, len = headers.length; i < len; i++)
            this.request.setRequestHeader(headers[i][0], headers[i][1]);

      this.request.send(midori.inArray(verb, ['POST', 'PUT']) ? what : null);
   }
}




function midoriAutoComplete(vars)
// Accepted params: id, minChars, separator, suggestionClass, suggestionSelectedClass, htmlTemplate, fileName, params, callback, callback2
{
   var thisObj = this;


   this.process = function (event)
   {
      if (this.popup && this.popup == midoriPopup.activePopup)
         switch (event.keyCode)
            {
               case 27 :
               case 37 :
               case 39 : midoriPopup.hide(); break;
               case 13 : this.replaceSnippet(this.snippet); midoriPopup.hide(); break;
               case 38 : if (this.suggestionPos && this.suggestionPos != 1) this.highlightSuggestion(this.suggestionPos - 1); break;
               case 40 : if (this.suggestionPos != this.numSuggestions) this.highlightSuggestion(this.suggestionPos + 1); break;
            }
      else if (vars.callback2)
         vars.callback2(event);

      this.content = this.obj.value;
      if (this.content == this.oldContent || midori.inArray(event.keyCode, [13, 38, 40]))
         return;

      var changed = false;
      for (var i = 0, len = this.content.length; i < len; i++)
         if (this.content.charAt(i) != this.oldContent.charAt(i))
            {
               changed = true;
               break;
            }
      if (!changed && this.oldContent.length < len)
         return;

      if (this.content.charAt(i) == vars.separator)
         (event.keyCode == 8) ? i-- : i++;

      for (var j = i; j > 0; j--)
         if (this.content.charAt(j) == vars.separator)
            {
               j++;
               break;
            }
      var snippet = this.content.substr(j, i - j);

      for (var j = i; j < len; j++)
         if (this.content.charAt(j) == vars.separator)
            break;
      snippet += this.content.substr(i, j - i);

      snippet = midori.trim(snippet);
      if (snippet.length >= vars.minChars)
         {
            if (typeof vars.fileName == 'string')
               this.ajax.post(vars.fileName, vars.params + midori.trim(snippet));
            else
               {
                  this.ajax.responseText = vars.fileName(vars.params + midori.trim(snippet));
                  this.ajaxCallback();
               }
         }

      this.oldContent = this.content;
   }


   this.addProperties = function (id, snippet)
   {
      var obj = midori.get('#midori_suggestion' + this.uniqid + '_' + id);
      midori.addEventListener(obj, 'mouseover', function (e) { thisObj.highlightSuggestion(id) } );
      midori.addEventListener(obj, 'click',     function (e) { thisObj.replaceSnippet(snippet); midori.preventDefault(e); } );
   }


   this.showSuggestions = function (snippet)
   {
      var suggestions = this.suggestions[snippet], html = '', properties = [], i = 0, j = 0, k = '';
      for (i in suggestions)
         {
            if (vars.htmlTemplate)
               for (k in suggestions[i])
                  properties[k] = suggestions[i][k];
            html += '<a id="midori_suggestion' + this.uniqid + '_' + (++j) + '" class="' + vars.suggestionClass + '" href="#">' +
                    (vars.htmlTemplate ? midori.replace(vars.htmlTemplate, properties) : suggestions[i]) + '</a>\n';
         }
      if (!html)
         {
            midoriPopup.hide();
            return;
         }

      this.snippet        = snippet;
      this.suggestionPos  = 0;
      this.numSuggestions = j;

      if (this.popup) midori.removeNode(this.popup);

      this.popupId = 'midori_suggestions' + this.uniqid;
      this.popup   = document.createElement('div');
      midori.setAttributes(this.popup, { id: this.popupId, className: 'popup' } );
      document.body.appendChild(this.popup);
      this.popup.innerHTML = html;

      j = 0;
      for (var i in this.suggestions[snippet])
         this.addProperties(++j, snippet);

      midoriPopup.show( { obj: this.obj, popupId: this.popupId, x: 0, y: 0, noAnim: true } );
      this.obj.focus();
   }


   this.highlightSuggestion = function (suggestionPos)
   {
      midori.get('#' + this.popupId + ' .' + vars.suggestionSelectedClass.replace(' ', '.')).apply("className = '" + vars.suggestionClass + "'");
      midori.get('#midori_suggestion' + this.uniqid + '_' + suggestionPos).className = vars.suggestionSelectedClass;
      this.suggestionPos = suggestionPos;
   }


   this.replaceSnippet = function (snippet)
   {
      var pos = 0;
      for (var i in this.suggestions[snippet])
         if (++pos == this.suggestionPos)
            {
               this.obj.value = this.obj.value.replace(snippet,
                  vars.callback ? vars.callback(this.suggestions[snippet][i]) : this.suggestions[snippet][i]);
               break;
            }
      this.content    = this.obj.value;
      this.oldContent = this.content;
      this.obj.focus();
   }


   this.init = function ()
   {
      if (!vars.separator)
         vars.separator = '';

      this.ajaxCallback = function () {
         if (thisObj.ajax.responseText)
            {
               var response                             = (typeof thisObj.ajax.responseText == 'string') ? eval('(' + thisObj.ajax.responseText + ')') : thisObj.ajax.responseText;
               thisObj.suggestions[response['snippet']] = response['result'];
               thisObj.showSuggestions(response['snippet']);
            }
      };

      this.uniqid      = midori.uniqid();
      this.obj         = midori.get('#' + vars.id);
      this.content     = this.obj.value;
      this.oldContent  = this.content;
      this.suggestions = [];
      this.ajax        = (typeof vars.fileName == 'string') ? new midoriAjax(this.ajaxCallback, '', true) : {};

      if (this.browserType != 'Gecko') // IE & Safari form submit fix
         {
            var parentNode = this.obj;
            midori.addEventListener(parentNode, 'keypress', function (e) { if (e.keyCode == 13) midori.preventDefault(e) } );
            while (parentNode.parentNode != null)
               {
                  parentNode = parentNode.parentNode;
                  if (parentNode.nodeName.toLowerCase() == 'form')
                     midori.addEventListener(parentNode, 'keypress', function (e) { if (e.keyCode == 13) return false } );
               }
         }

      this.obj.setAttribute('autocomplete', 'off');
      midori.addEventListener(this.obj, 'keyup', function (e) { thisObj.process(e) } );
   }

   this.init();
}




function midoriInlineEdit(vars)
// Accepted params: id, size, maxlen, textArea, callback, x, y
{
   var thisObj  = this;
   this.myObj   = midori.get('#' + vars.id);
   this.editObj = '';
   this.input   = vars.textArea ? 'textarea' : 'input';

   midori.addEventListener(document, 'mouseup', function (e) { if (thisObj.editObj && midori.getEventTarget(e) != thisObj.editObj) { thisObj.save(); midori.preventDefault(e); } } );


   this.edit = function ()
   {
      if (this.myObj.getAttribute('editing') == 'on' || midori.get(this.input, this.myObj)[0])
         return;
      var value = this.myObj.innerHTML.replace(/"/g, '&quot;');
      this.myObj.innerHTML = vars.textArea ?
         midori.replace('<textarea style="width: :w; height: :h; overflow: auto">:value</textarea>',
            { w: (this.myObj.parentNode.offsetWidth - (vars.x ? vars.x : 32)) + 'px', h: (this.myObj.parentNode.offsetHeight - (vars.y ? vars.y : 32)) + 'px', value: value } ) :
         midori.replace('<input type="text" size=":size" maxlength=":maxlen" value=":value" />',
            { size: (vars.size ? vars.size : ''), maxlen: (vars.maxlen ? vars.maxlen : ''), value: value } );
      this.editObj = midori.get(this.input, this.myObj)[0];
      this.editObj.focus(); this.editObj.focus(); // Necessary when used inside drag & drop
      this.myObj.setAttribute('editing', 'on');
      midori.addEventListener(this.editObj, 'mousedown', function (e) { midori.preventBubble(e) } );
      midori.addEventListener(this.editObj, 'keyup',     function (e) { if (e.keyCode == 13 || e.keyCode == 27) { thisObj.save() } } );
      midori.addEventListener(this.editObj, 'blur',      function (e) { thisObj.save() } );
   }


   this.select = function ()
   {
      if (this.myObj.getAttribute('editing') == 'on') this.editObj.select();
   }


   this.save = function ()
   {
      if (this.myObj.getAttribute('editing') != 'on')
         return;
      var text = midori.trim(this.editObj.value.replace('\n', '').replace('\r', ''));
      if (!text)
         {
            this.editObj.value = '';
            return;
         }
      this.myObj.setAttribute('editing', 'off');
      this.myObj.innerHTML = ''; // Bugfix for Safari
      this.myObj.innerHTML = text;
      if (vars.callback) vars.callback(text, this.myObj);
   }
}
