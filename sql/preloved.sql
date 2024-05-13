DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS user_cart;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS chatrooms;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS purchaseData;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS tags_predefined;
DROP TABLE IF EXISTS tags_values;
DROP TABLE IF EXISTS conditions;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS shippingCode;
DROP TABLE IF EXISTS currency;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS item_images;

CREATE TABLE images (
    id VARCHAR PRIMARY KEY
);

CREATE TABLE item_images (
    image VARCHAR REFERENCES images,
    item VARCHAR REFERENCES items
);

CREATE TABLE categories (
    category VARCHAR PRIMARY KEY
);

CREATE TABLE tags (
    id VARCHAR PRIMARY KEY,
    category VARCHAR REFERENCES categories,
    tag VARCHAR
);

CREATE TABLE tags_predefined (
    tag VARCHAR references tags,
    value VARCHAR
);

CREATE TABLE tags_values (
    item VARCHAR references items,
    tag VARCHAR references tags,
    data VARCHAR
);

CREATE TABLE user_cart (
   user VARCHAR references users,
   item VARCHAR references items
);

CREATE TABLE purchaseData (
   buyer VARCHAR references users,
   deliveryDate INTEGER,
   state VARCHAR,
   address VARCHAR,
   city VARCHAR,
   postalCode VARCHAR,
   id VARCHAR PRIMARY KEY
);

CREATE TABLE purchases (
    item     VARCHAR references items,
    purchase VARCHAR references purchases
);

CREATE TABLE items (
    id VARCHAR PRIMARY KEY,
    name VARCHAR NOT NULL,
    price REAL NOT NULL,
    category VARCHAR references categories,
    date INTEGER,                             -- date the item was published
    description VARCHAR,
    mainImage VARCHAR REFERENCES images,
    creator VARCHAR REFERENCES users,
    sold INTEGER DEFAULT 0
);

CREATE TABLE users (
  user_id VARCHAR PRIMARY KEY,
  username VARCHAR UNIQUE,
  password VARCHAR,                  -- password stored in sha-1
  name VARCHAR,                       -- real name
  email VARCHAR UNIQUE,
  phone VARCHAR,
  currency VARCHAR references currency,
  image VARCHAR REFERENCES images DEFAULT 0,
  role VARCHAR(30) REFERENCES roles(name) DEFAULT 'user'
);

CREATE TABLE roles (
    name VARCHAR(30) PRIMARY KEY
);

CREATE TABLE comments (
  id VARCHAR PRIMARY KEY,            -- comment id
  mainuser VARCHAR REFERENCES users,   -- user this comment is about
  userc VARCHAR REFERENCES users, -- user that wrote the comment
  date INTEGER,                 -- date when news item was published in epoch format
  text VARCHAR,                       -- comment text
  rating INTEGER
);

CREATE TABLE favorites (
  user VARCHAR REFERENCES users,
  item VARCHAR REFERENCES items
);

CREATE TABLE messages (
    chatroom VARCHAR NOT NULL REFERENCES chatrooms,
    sender VARCHAR NOT NULL REFERENCES users,
    sentTime INTEGER NOT NULL,
    readTime INTEGER DEFAULT NULL,
    message VARCHAR NOT NULL
);

CREATE TABLE chatrooms (
   chatroom_id VARCHAR PRIMARY KEY,
   item_id VARCHAR NOT NULL REFERENCES items,
   seller_id VARCHAR NOT NULL REFERENCES users,
   buyer_id VARCHAR NOT NULL REFERENCES users
);

INSERT INTO roles VALUES ('user'), ('admin');

CREATE TABLE shippingCode (
    code VARCHAR
);

CREATE TABLE currency (
    code VARCHAR PRIMARY KEY,
    value REAL,
    symbol VARCHAR
);

INSERT INTO images VALUES
    ('0'), --profile.png
    ('1'); --flower.png

INSERT INTO item_images (item, image) VALUES
    ('1','1'),
    ('2','1'),
    ('3','1'),
    ('4','1'),
    ('5','1'),
    ('6','1'),
    ('7','1'),
    ('8','1'),
    ('9','1'),
    ('10','1'),
    ('11','1'),
    ('12','1'),
    ('13','1'),
    ('14','1'),
    ('15','1'),
    ('16','1'),
    ('17','1'),
    ('18','1'),
    ('19','1'),
    ('20','1');


INSERT INTO users (user_id, password, name, email, phone, username, currency)
VALUES
    ('1', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'John Doe', 'john@example.com', '1234567890', 'johny', 'USD'),
    ('2', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Jane Smith', 'jane@example.com', '9876543210', 'janey', 'EUR'),
    ('3', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Alice Wonder', 'alice@example.com', '5551234567', 'alicewonderful', 'GBP'),
    ('4', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Bob Green', 'bob@example.com', '4447890123', 'bobby', 'INR'),
    ('5', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Sarah Jones', 'sarah@example.com', '9998887777', 'sarita', 'AUD'),
    ('6', 'password321', 'Mike Andrews', 'mike@example.com', '1112223333', 'mikeymouse', 'CAD'),
    ('7', 'brownie', 'Emily Brown', 'emily@example.com', '7776665555', 'emiliii', 'SGD'),
    ('8', 'king123', 'Alex King', 'alex@example.com', '2223334444', 'kingofall', 'CHF'),
    ('9', 'sammy123', 'Sam Carter', 'sam@example.com', '6667778888', 'carteiro', 'MYR'),
    ('10', 'lisalisa', 'Lisa Adams', 'lisa@example.com', '3334445555', 'lisa', 'JPY');
--
INSERT INTO users (user_id, password, name, email, phone, username, role, currency) VALUES
    ('11','cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Lisa Adams2', 'lisa2@example.com', '3334445555', 'lisa2', 'admin', 'EUR');

INSERT INTO items (id, name, price, date, description, creator, mainImage, category, sold)
VALUES
    ('1','Guitar', 299.99, '2024-04-10', 'Classic electric guitar.', '1','1', 'music', 1),
    ('2','Laptop', 899.99, '2024-04-10', 'High-performance laptop.', '2','1', 'technology', 1),
    ('3','Watch', 199.99, '2024-04-10', 'Luxury watch with timeless design.', '3','1', 'technology', 1),
    ('4','Bicycle', 399.00, '2024-04-10', 'Mountain bike for all terrains.', '4','1', 'sports', 1),
    ('5','Camera', 499.50, '2024-04-10', 'Great DSLR camera for beginners.', '5','1', '', 0),
    ('6','Smartphone', 599.99, '2024-04-10', 'Latest smartphone with advanced features.', '6','1', 'technology', 0),
    ('7','Television', 799.99, '2024-04-10', '4K HDR smart TV for immersive viewing.', '7','1', 'technology', 0),
    ('8','Desk', 149.50, '2024-04-10', 'Simple desk for home office.', '8','1', 'furniture', 0),
    ('9','Headphones', 99.99, '2024-04-10', 'Noise-cancelling wireless headphones.', '9','1', 'technology', 0),
    ('10','Backpack', 49.99, '2024-04-10', 'Durable backpack for everyday use.', '10','1', '', 0);




INSERT INTO comments (id, mainuser, userc, date, text, rating)
VALUES
    ('1', '1', '2', '2024-04-10', 'Great guitar!', 5),
    ('2', '2', '1', '2024-04-10', 'Excellent laptop.', 4),
    ('3', '3', '2', '2024-04-10', 'Nice camera!', 4),
    ('4', '4', '3', '2024-04-10', 'Awesome watch.', 5),
    ('5', '1', '2', '2024-04-10', 'Love this bike!', 5),
    ('6', '1', '3', '2024-04-10', 'Fantastic phone.', 4);

INSERT INTO favorites (user, item)
VALUES
    ('john_doe', '1'),
    ('jane_smith', '2'),
    ('alice_wonder', '2'),
    ('alice_wonder', '5'),
    ('bob_green', '3'),
    ('sarah_jones', '4'),
    ('mike_andrews', '6'),
    ('emily_brown', '7'),
    ('alex_king', '8'),
    ('sam_carter', '9'),
    ('lisa_adams', '10');


INSERT INTO user_cart (user, item)
VALUES
    ('1', 'Guitar'),
    ('2', 'Laptop'),
    ('3', 'Camera'),
    ('4', 'Watch'),
    ('5', 'Bicycle'),
    ('6', 'Smartphone'),
    ('7', 'Television'),
    ('8', 'Desk'),
    ('9', 'Headphones'),
    ('10', 'Backpack');

INSERT INTO chatrooms (chatroom_id, item_id, seller_id, buyer_id)
VALUES
    ('1','1', '1', '2'),
    ('2','2', '3', '4'),
    ('3','3', '1', '4');

INSERT INTO messages (chatroom, sender, sentTime, readTime, message)
VALUES
    ('1', '1', 1648732345, NULL, 'Hi there! Is item 10 still available?'),
    ('1', '1', 1648732345, NULL, 'Hi there! Is item 10 still available?'),
    ('1', '2', 1648741234, 1648750000, 'Yes, item 10 is available. Are you interested in purchasing it?'),
    ('1', '1', 1648763123, NULL, 'Great! When can we meet for the transaction?'),
    ('1', '2', 1648780098, NULL, 'I am available on weekends. How about Saturday afternoon?'),
    ('1', '1', 1648791234, NULL, 'Saturday afternoon works for me. Let''s confirm the details.'),
    ('1', '2', 1648802345, NULL, 'Perfect! Let''s meet at the coffee shop near the park.'),
    ('1', '1', 1648813456, NULL, 'See you there!'),
    ('1', '2', 1648824567, NULL, 'Looking forward to it!'),
    ('1', '1', 1648835678, NULL, 'I''ll be wearing a blue jacket.'),
    ('1', '2', 1648846789, NULL, 'Got it! I''ll be in a red hat.'),
    ('1', '1', 1648857890, NULL, 'Did you arrive?'),
    ('1', '2', 1648868901, NULL, 'I''m here. Where are you?'),
    ('1', '1', 1648880012, NULL, 'I''m near the entrance.'),
    ('1', '2', 1648891123, NULL, 'I see you!'),
    ('1', '1', 1648902234, NULL, 'Let''s finalize the transaction.'),
    ('1', '2', 1648913345, NULL, 'Sure, here''s the payment.'),
    ('2', '3', 1648732345, NULL, 'Hello! I''m interested in item 11.'),
    ('2', '4', 1648741234, 1648750000, 'Great! It''s still available.'),
    ('2', '3', 1648763123, NULL, 'Can we arrange a meetup?'),
    ('2', '4', 1648780098, NULL, 'Sure, when are you available?'),
    ('2', '3', 1648791234, NULL, 'I''m free on weekdays after 5 PM.'),
    ('2', '4', 1648802345, NULL, 'How about Thursday evening?'),
    ('2', '3', 1648813456, NULL, 'Thursday evening works for me.'),
    ('2', '4', 1648824567, NULL, 'Let''s meet at the shopping mall.'),
    ('2', '3', 1648835678, NULL, 'Sounds good. See you there!'),
    ('2', '4', 1648846789, NULL, 'See you on Thursday!'),
    ('2', '3', 1648857890, NULL, 'I''m wearing a black hat.'),
    ('2', '4', 1648868901, NULL, 'Got it! I''ll be in a green shirt.'),
    ('2', '3', 1648880012, NULL, 'Let''s finalize the details on Thursday.'),
    ('2', '4', 1648891123, NULL, 'Sure, looking forward to it!'),
    ('2', '3', 1648902234, NULL, 'See you soon.'),
    ('2', '4', 1648913345, NULL, 'Take care!'),
    ('3', '1', 1648902234, NULL, 'See you soon.');


INSERT INTO purchaseData (id, buyer, deliveryDate, state, address, city, postalCode) VALUES
    ('1','1', '2/10/2020', 'shipping', 'Rua very', 'Valbom', '4420-150'),
    ('2','1','10/10/2024', 'preparing', 'Rua pocuo', 'Caniço', '4480-220'),
    ('3','4', '10/10/2024', 'preparing', 'Rua muiro', 'Lisbon', '4500-209'),
    ('4','2', '10/10/2025', 'delivered', 'Ruaaa', 'Porto', '6492-943');

INSERT INTO purchases (item, purchase) VALUES
                                           ('2', '1'),
                                           ('3', '1'),
                                           ('4', '2'),
                                           ('1', '3');

INSERT INTO categories (category) VALUES
                                      (''),
                                      ('clothes'),
                                      ('technology'),
                                      ('toys'),
                                      ('cars'),
                                      ('books'),
                                      ('furniture'),
                                      ('music'),
                                      ('sports');
INSERT INTO tags (id, category, tag) VALUES
                                     ('1','', 'condition'),
                                     ('2','', 'tag'),
                                     ('3','clothes', 'size'),
                                     ('4','clothes', 'brand'),
                                     ('5','cars', 'model'),
                                     ('6','toys', 'brand'),
                                     ('7','books', 'author');


INSERT INTO tags_predefined (tag, value) VALUES
                                    ('1', 'new'),
                                    ('1', 'old'),
                                    ('1', 'used'),
                                    ('3', 'S'),
                                    ('3', 'M'),
                                    ('3', 'L');

INSERT INTO tags_values (item, tag, data) VALUES
                                     ('2', '5', 'Cassandra Clare'),
                                     ('3', '3', 'S'),
                                     ('4', '3', 'S'),
                                     ('5', '3', 'M'),
                                     ('4', '2', 'bela'),
                                     ('1', '1', 'new'),
                                     ('2', '1', 'old'),
                                     ('3', '1', 'new'),
                                     ('4', '1', 'used'),
                                     ('5', '1', 'new'),
                                     ('6', '1', 'old'),
                                     ('3', '2', 'bel');

INSERT INTO shippingCode (code) VALUES ('abc'), ('hey');

INSERT INTO currency (code, value, symbol) VALUES
                                               ('EUR', 1.000000, '€'),
                                               ('USD', 1.070080, '$'),
                                               ('GBP', 0.854439, '£'),
                                               ('INR', 89.310771, '₹'),
                                               ('AUD', 1.633299, '$'),
                                               ('CAD', 1.462732, '$'),
                                               ('SGD', 1.456636, '$'),
                                               ('CHF', 0.977023, 'Fr'),
                                               ('MYR', 5.102757, 'RM'),
                                               ('JPY', 167.684120, '¥'),
                                               ('CNY', 7.750000, '¥');