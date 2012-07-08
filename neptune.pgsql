CREATE TABLE neptune_menu (
  position integer NOT NULL,
  path text NOT NULL,
  name text NOT NULL,
  type integer NOT NULL
);

CREATE TABLE neptune_pages (
  pid text NOT NULL,
  name text NOT NULL,
  content text NOT NULL,
  author text NOT NULL,
  created timestamp DEFAULT current_timestamp,
  editor text NOT NULL,
  edited timestamp DEFAULT current_timestamp,
  bbcode integer NOT NULL,
  commenting integer NOT NULL
);

CREATE TABLE neptune_users (
  username varchar(255) NOT NULL PRIMARY KEY,
  displayname text NOT NULL,
  password text NOT NULL,
  email text,
  email_public integer NOT NULL DEFAULT '0',
  permissions integer NOT NULL DEFAULT '1',
  joined timestamp DEFAULT current_timestamp,
  active timestamp DEFAULT current_timestamp,
  postcount integer NOT NULL DEFAULT '0',	
  avatar_type integer NOT NULL DEFAULT '0',
  avatar text,
  signature text 
);