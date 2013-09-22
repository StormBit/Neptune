CREATE SEQUENCE neptune_home_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
CREATE TABLE neptune_blog
(
  id integer NOT NULL DEFAULT nextval('neptune_home_id_seq'::regclass),
  title text NOT NULL,
  content text NOT NULL,
  author text NOT NULL,
  created timestamp without time zone DEFAULT now(),
  editor text NOT NULL,
  edited timestamp without time zone,
  bbcode integer NOT NULL,
  commenting integer NOT NULL,
  sticky integer NOT NULL
)

CREATE TABLE neptune_menu
(
  "position" integer NOT NULL,
  path text NOT NULL,
  name text NOT NULL,
  type integer NOT NULL
)
CREATE TABLE neptune_pages
(
  pid text NOT NULL,
  name text NOT NULL,
  content text NOT NULL,
  author text NOT NULL,
  created timestamp without time zone DEFAULT now(),
  editor text NOT NULL,
  edited timestamp without time zone DEFAULT now(),
  bbcode integer NOT NULL,
  commenting integer NOT NULL
)
CREATE TABLE neptune_users
(
  username character varying(255) NOT NULL,
  displayname text NOT NULL,
  password text NOT NULL,
  email text,
  email_public integer NOT NULL DEFAULT 0,
  permissions integer NOT NULL DEFAULT 1,
  joined timestamp without time zone DEFAULT now(),
  active timestamp without time zone DEFAULT now(),
  postcount integer NOT NULL DEFAULT 0,
  avatar_type integer NOT NULL DEFAULT 0,
  avatar text,
  signature text,
  CONSTRAINT neptune_users_pkey PRIMARY KEY (username)
)