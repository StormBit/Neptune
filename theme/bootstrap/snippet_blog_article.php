<?php
				if (@$notFirstArticle) {
					$NeptuneCore->var_append("output","blog-body",'<hr>');
				} else {
					$notFirstArticle = true;
				}
				$NeptuneCore->var_append("output","blog-body",'<div class="content-area blog-article">
                    <h2>' . $NeptuneCore->var_get("output","title_prepend") . '<a href="?article/' . $result["id"] . '">' . $NeptuneCore->var_get("output","title") . $NeptuneCore->var_get("output","title_append") . '</a></h2>');
                    if ($NeptuneCore->var_get("output","subtitle") != "") {
                        $NeptuneCore->var_append("output","blog-body","<p><small>" . $NeptuneCore->var_get("output","subtitle") . "</small></p>\n");
                    }
                    $NeptuneCore->var_append("output","blog-body","\n" . $NeptuneCore->var_get("output","body") . "\n" . ' 
                </div>'); 
				
				$NeptuneCore->var_clear("output","title_prepend");
				$NeptuneCore->var_clear("output","title");
				$NeptuneCore->var_clear("output","title_append");
				$NeptuneCore->var_clear("output","subtitle");
				$NeptuneCore->var_clear("output","body");
				?>