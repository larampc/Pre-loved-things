DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS item_images;
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

CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category VARCHAR UNIQUE
);

CREATE TABLE tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category INTEGER REFERENCES categories,
    tag VARCHAR
);

CREATE TABLE tags_predefined (
    tag INTEGER references tags,
    value VARCHAR
);

CREATE TABLE tags_values (
    item INTEGER references items,
    tag INTEGER references tags,
    data VARCHAR
);

CREATE TABLE item_images (
    imagePath VARCHAR,
    item INTEGER references items
);


CREATE TABLE user_cart (
   user INTEGER references users,
   item INTEGER references items
);

CREATE TABLE purchaseData (
   buyer INTEGER references users,
   deliveryDate INTEGER,
   state VARCHAR,
   address VARCHAR,
   city VARCHAR,
   postalCode VARCHAR,
   id INTEGER PRIMARY KEY AUTOINCREMENT
);

CREATE TABLE purchases (
    item     INTEGER references items,
    purchase INTEGER references purchases
);

CREATE TABLE items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR NOT NULL,
    price REAL NOT NULL,
    condition VARCHAR references conditions,
    category INTEGER references categories,
    date INTEGER,                             -- date the item was published
    description VARCHAR,
    mainImage VARCHAR,
    creator INTEGER REFERENCES users,
    sold INTEGER DEFAULT 0
);

CREATE TABLE users (
  user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  username VARCHAR UNIQUE,
  password VARCHAR,                  -- password stored in sha-1
  name VARCHAR,                       -- real name
  email VARCHAR UNIQUE,
  phone VARCHAR,
  photoPath VARCHAR DEFAULT 'profile.png',
  role VARCHAR(30) REFERENCES roles(name) DEFAULT 'user'
);

CREATE TABLE roles (
    name VARCHAR(30) PRIMARY KEY
);

CREATE TABLE comments (
  id INTEGER PRIMARY KEY,            -- comment id
  mainuser INTEGER REFERENCES users,   -- user this comment is about
  userc INTEGER REFERENCES users, -- user that wrote the comment
  date INTEGER,                 -- date when news item was published in epoch format
  text VARCHAR,                       -- comment text
  rating INTEGER
);

CREATE TABLE favorites (
  user INTEGER REFERENCES users,
  item INTEGER REFERENCES items
);

CREATE TABLE messages (
    chatroom INTEGER NOT NULL REFERENCES chatrooms,
    sender INTEGER NOT NULL REFERENCES users,
    sentTime INTEGER NOT NULL,
    readTime INTEGER DEFAULT NULL,
    message VARCHAR NOT NULL
);

CREATE TABLE chatrooms (
   chatroom_id INTEGER PRIMARY KEY AUTOINCREMENT,
   item_id INTEGER NOT NULL REFERENCES items,
   seller_id INTEGER NOT NULL REFERENCES users,
   buyer_id INTEGER NOT NULL REFERENCES users
);

CREATE TABLE conditions (
    condition VARCHAR PRIMARY KEY
);

INSERT INTO roles VALUES ('user'), ('admin');

CREATE TABLE shippingCode (
    code VARCHAR
);

INSERT INTO item_images (item, imagePath) VALUES
    (1,'flower.png'),
    (2,'flower.png'),
    (3,'flower.png'),
    (4,'flower.png'),
    (5,'flower.png'),
    (6,'flower.png'),
    (7,'flower.png'),
    (8,'flower.png'),
    (9,'flower.png'),
    (10,'flower.png'),
    (11,'flower.png'),
    (12,'flower.png'),
    (13,'flower.png'),
    (14,'flower.png'),
    (15,'flower.png'),
    (16,'flower.png'),
    (17,'flower.png'),
    (18,'flower.png'),
    (19,'flower.png'),
    (20,'flower.png');


INSERT INTO users (password, name, email, phone, username)
VALUES
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'John Doe', 'john@example.com', '1234567890', 'johny'),
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Jane Smith', 'jane@example.com', '9876543210', 'janey'),
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Alice Wonder', 'alice@example.com', '5551234567', 'alicewonderful'),
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Bob Green', 'bob@example.com', '4447890123', 'bobby'),
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Sarah Jones', 'sarah@example.com', '9998887777', 'sarita'),
    ('password321', 'Mike Andrews', 'mike@example.com', '1112223333', 'mikeymouse'),
    ('brownie', 'Emily Brown', 'emily@example.com', '7776665555', 'emiliii'),
    ('king123', 'Alex King', 'alex@example.com', '2223334444', 'kingofall'),
    ('sammy123', 'Sam Carter', 'sam@example.com', '6667778888', 'carteiro'),
    ('lisalisa', 'Lisa Adams', 'lisa@example.com', '3334445555', 'lisa');

INSERT INTO users (password, name, email, phone, username, role) VALUES
    ('cbfdac6008f9cab4083784cbd1874f76618d2a97', 'Lisa Adams2', 'lisa2@example.com', '3334445555', 'lisa2', 'admin');

INSERT INTO items (name, price, condition, date, description, creator, mainImage, category)
VALUES
    ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 1),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 2),
    ('Watch', 199.99, 'used', '2024-04-10', 'Luxury watch with timeless design.', 3, 'flower.png', 2),
    ('Bicycle', 399.00, 'new', '2024-04-10', 'Mountain bike for all terrains.', 4, 'flower.png', 2),
    ('Camera', 499.50, 'new', '2024-04-10', 'Great DSLR camera for beginners.', 5, 'flower.png', 2),
    ('Smartphone', 599.99, 'new', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 6),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 7),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 1),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 2),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 3),
    ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 4),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 5),
    ('Camera', 499.50, 'use', '2024-04-10', 'Great DSLR camera for beginners.', 3, 'flower.png', 6),
    ('Watch', 199.99, 'new', '2024-04-10', 'Luxury watch with timeless design.', 4, 'flower.png', 7),
    ('Bicycle', 399.00, 'old', '2024-04-10', 'Mountain bike for all terrains.', 5, 'flower.png', 1),
    ('Smartphone', 599.99, 'old', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 2),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 3),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 4),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 5), ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 1),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 2),
    ('Watch', 199.99, 'used', '2024-04-10', 'Luxury watch with timeless design.', 3, 'flower.png', 3),
    ('Bicycle', 399.00, 'new', '2024-04-10', 'Mountain bike for all terrains.', 4, 'flower.png', 2),
    ('Camera', 499.50, 'new', '2024-04-10', 'Great DSLR camera for beginners.', 5, 'flower.png', 2),
    ('Smartphone', 599.99, 'new', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 6),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 7),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 1),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 2),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 3),
    ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 4),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 5),
    ('Camera', 499.50, 'use', '2024-04-10', 'Great DSLR camera for beginners.', 3, 'flower.png', 6),
    ('Watch', 199.99, 'new', '2024-04-10', 'Luxury watch with timeless design.', 4, 'flower.png', 7),
    ('Bicycle', 399.00, 'old', '2024-04-10', 'Mountain bike for all terrains.', 5, 'flower.png', 1),
    ('Smartphone', 599.99, 'old', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 2),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 3),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 4),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 5),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 6),
    ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 1),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 2),
    ('Watch', 199.99, 'used', '2024-04-10', 'Luxury watch with timeless design.', 3, 'flower.png', 3),
    ('Bicycle', 399.00, 'new', '2024-04-10', 'Mountain bike for all terrains.', 4, 'flower.png', 2),
    ('Camera', 499.50, 'new', '2024-04-10', 'Great DSLR camera for beginners.', 5, 'flower.png', 2),
    ('Smartphone', 599.99, 'new', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 6),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 7),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 1),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 2),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 3),
    ('Guitar', 299.99, 'used', '2024-04-10', 'Classic electric guitar.', 1, 'flower.png', 4),
    ('Laptop', 899.99, 'new', '2024-04-10', 'High-performance laptop.', 2, 'flower.png', 5),
    ('Camera', 499.50, 'use', '2024-04-10', 'Great DSLR camera for beginners.', 3, 'flower.png', 6),
    ('Watch', 199.99, 'new', '2024-04-10', 'Luxury watch with timeless design.', 4, 'flower.png', 7),
    ('Bicycle', 399.00, 'old', '2024-04-10', 'Mountain bike for all terrains.', 5, 'flower.png', 1),
    ('Smartphone', 599.99, 'old', '2024-04-10', 'Latest smartphone with advanced features.', 6, 'flower.png', 2),
    ('Television', 799.99, 'new', '2024-04-10', '4K HDR smart TV for immersive viewing.', 7, 'flower.png', 3),
    ('Desk', 149.50, 'used', '2024-04-10', 'Simple desk for home office.', 8, 'flower.png', 4),
    ('Headphones', 99.99, 'new', '2024-04-10', 'Noise-cancelling wireless headphones.', 9, 'flower.png', 5),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 6),
    ('Backpack', 49.99, 'new', '2024-04-10', 'Durable backpack for everyday use.', 10, 'flower.png', 6);


INSERT INTO comments (mainuser, userc, date, text, rating)
VALUES
    (1, 2, '2024-04-10', 'Great guitar!', 5),
    (2, 1, '2024-04-10', 'Excellent laptop.', 4),
    (3, 2, '2024-04-10', 'Nice camera!', 4),
    (4, 3, '2024-04-10', 'Awesome watch.', 5),
    (1, 2, '2024-04-10', 'Love this bike!', 5),
    (1, 3, '2024-04-10', 'Fantastic phone.', 4);

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
    ('john_doe', 'Guitar'),
    ('jane_smith', 'Laptop'),
    ('alice_wonder', 'Camera'),
    ('bob_green', 'Watch'),
    ('sarah_jones', 'Bicycle'),
    ('mike_andrews', 'Smartphone'),
    ('emily_brown', 'Television'),
    ('alex_king', 'Desk'),
    ('sam_carter', 'Headphones'),
    ('lisa_adams', 'Backpack');

INSERT INTO chatrooms (chatroom_id, item_id, seller_id, buyer_id)
VALUES
    (1, 10, 1, 2),
    (2, 11, 3, 4),
    (3, 12, 1, 4);

INSERT INTO messages (chatroom, sender, sentTime, readTime, message)
VALUES
    (1, 1, 1648732345, NULL, 'Hi there! Is item 10 still available?'),
    (1, 1, 1648732345, NULL, 'Hi there! Is item 10 still available?'),
    (1, 2, 1648741234, 1648750000, 'Yes, item 10 is available. Are you interested in purchasing it?'),
    (1, 1, 1648763123, NULL, 'Great! When can we meet for the transaction?'),
    (1, 2, 1648780098, NULL, 'I am available on weekends. How about Saturday afternoon?'),
    (1, 1, 1648791234, NULL, 'Saturday afternoon works for me. Let''s confirm the details.'),
    (1, 2, 1648802345, NULL, 'Perfect! Let''s meet at the coffee shop near the park.'),
    (1, 1, 1648813456, NULL, 'See you there!'),
    (1, 2, 1648824567, NULL, 'Looking forward to it!'),
    (1, 1, 1648835678, NULL, 'I''ll be wearing a blue jacket.'),
    (1, 2, 1648846789, NULL, 'Got it! I''ll be in a red hat.'),
    (1, 1, 1648857890, NULL, 'Did you arrive?'),
    (1, 2, 1648868901, NULL, 'I''m here. Where are you?'),
    (1, 1, 1648880012, NULL, 'I''m near the entrance.'),
    (1, 2, 1648891123, NULL, 'I see you!'),
    (1, 1, 1648902234, NULL, 'Let''s finalize the transaction.'),
    (1, 2, 1648913345, NULL, 'Sure, here''s the payment.'),
    (2, 3, 1648732345, NULL, 'Hello! I''m interested in item 11.'),
    (2, 4, 1648741234, 1648750000, 'Great! It''s still available.'),
    (2, 3, 1648763123, NULL, 'Can we arrange a meetup?'),
    (2, 4, 1648780098, NULL, 'Sure, when are you available?'),
    (2, 3, 1648791234, NULL, 'I''m free on weekdays after 5 PM.'),
    (2, 4, 1648802345, NULL, 'How about Thursday evening?'),
    (2, 3, 1648813456, NULL, 'Thursday evening works for me.'),
    (2, 4, 1648824567, NULL, 'Let''s meet at the shopping mall.'),
    (2, 3, 1648835678, NULL, 'Sounds good. See you there!'),
    (2, 4, 1648846789, NULL, 'See you on Thursday!'),
    (2, 3, 1648857890, NULL, 'I''m wearing a black hat.'),
    (2, 4, 1648868901, NULL, 'Got it! I''ll be in a green shirt.'),
    (2, 3, 1648880012, NULL, 'Let''s finalize the details on Thursday.'),
    (2, 4, 1648891123, NULL, 'Sure, looking forward to it!'),
    (2, 3, 1648902234, NULL, 'See you soon.'),
    (2, 4, 1648913345, NULL, 'Take care!'),
    (3, 1, 1648902234, NULL, 'See you soon.');


INSERT INTO purchaseData (buyer, deliveryDate, state, address, city, postalCode) VALUES
    (1, '2/10/2020', 'shipping', 'Rua very', 'Valbom', '4420-150'),
    (1,'10/10/2024', 'preparing', 'Rua pocuo', 'Caniço', '4480-220'),
    (4, '10/10/2024', 'preparing', 'Rua muiro', 'Lisbon', '4500-209'),
    (2, '10/10/2025', 'delivered', 'Ruaaa', 'Porto', '6492-943');

INSERT INTO purchases (item, purchase) VALUES
                                           (2, 1),
                                           (3, 1),
                                           (4, 2),
                                           (1, 3);

INSERT INTO categories (category) VALUES
                                      (''),
                                      ('clothes'),
                                      ('technology'),
                                      ('toys'),
                                      ('cars'),
                                      ('books'),
                                      ('furniture'),
                                      ('sports');
INSERT INTO tags (category, tag) VALUES
                                     (1, 'tag'),
                                     (2, 'size'),
                                     (2, 'brand'),
                                     (3, 'model'),
                                     (3, 'brand'),
                                     (5, 'author');


INSERT INTO tags_predefined (tag, value) VALUES
                                    (2, 'S'),
                                    (2, 'M'),
                                    (2, 'L');

INSERT INTO tags_values (item, tag, data) VALUES
                                     (1, 4, 'Suzuki'),
                                     (2, 5, 'Cassandra Clare'),
                                     (3, 2, 'S'),
                                     (4, 2, 'S'),
                                     (5, 2, 'M'),
                                     (4, 3, 'bela'),
                                     (3, 3, 'bel');

INSERT INTO conditions (condition) VALUES
                                      ('new'),
                                      ('old'),
                                      ('used');

INSERT INTO shippingCode (code) VALUES ('abc'), ('hey');