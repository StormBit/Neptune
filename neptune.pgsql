CREATE TABLE neptune_blog (
    id serial NOT NULL,
    title text NOT NULL,
    content text NOT NULL,
    author text NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    editor text NOT NULL,
    edited timestamp without time zone NOT NULL,
    bbcode integer NOT NULL,
    commenting integer NOT NULL,
    sticky integer NOT NULL
);

CREATE TABLE neptune_menu (
    "position" integer NOT NULL,
    path text NOT NULL,
    name text NOT NULL,
    type integer NOT NULL
);

CREATE TABLE neptune_pages (
    pid text NOT NULL,
    name text NOT NULL,
    content text NOT NULL,
    author text NOT NULL,
    created timestamp without time zone DEFAULT now(),
    editor text NOT NULL,
    edited timestamp without time zone DEFAULT now(),
    bbcode integer NOT NULL,
    commenting integer NOT NULL
);

CREATE TABLE neptune_users (
    username character varying(255) NOT NULL,
    displayname text NOT NULL,
    password text NOT NULL,
    email text,
    email_public integer DEFAULT 0 NOT NULL,
    permissions integer DEFAULT 1 NOT NULL,
    joined timestamp without time zone DEFAULT now(),
    active timestamp without time zone DEFAULT now(),
    postcount integer DEFAULT 0 NOT NULL,
    avatar_type integer DEFAULT 0 NOT NULL,
    avatar text,
    signature text
);