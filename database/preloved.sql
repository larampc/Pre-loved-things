CREATE TABLE items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR NOT NULL,
    price REAL,
    category VARCHAR,       
    condition VARCHAR,                      
    size VARCHAR,                      
    brand VARCHAR,                      
    model VARCHAR,                      
    date INTEGER,                             -- date the item was published
    description VARCHAR,
    user REFERENCES users
);

CREATE TABLE users (
  username VARCHAR PRIMARY KEY,      -- unique username
  password VARCHAR,                  -- password stored in sha-1
  name VARCHAR,                       -- real name
  email VARCHAR,
  phone INTEGER
);

CREATE TABLE comments (
  id INTEGER PRIMARY KEY,            -- comment id
  mainuser INTEGER REFERENCES users,   -- user this comment is about
  userc VARCHAR REFERENCES users, -- user that wrote the comment
  date INTEGER,                 -- date when news item was published in epoch format
  text VARCHAR,                       -- comment text
  rating INTEGER
);

CREATE TABLE favorites (
  id INTEGER PRIMARY KEY,            
  user INTEGER REFERENCES users,   
  item VARCHAR REFERENCES items
);