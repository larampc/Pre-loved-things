# Pre-loved things

## Group ltw15g05

- Bruno Aguiar (up202205619) 33.33%
- Francisco Fernandes (up202208485) 33.33%
- Lara Coelho (up202208689) 33.33%

## Install Instructions

    git clone https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw15g05.git
    git checkout final-delivery-v1
    sqlite3 data/preloved.db < sql/preloved.sql
    sudo apt-get install php-gd (install php-gd if it is not already)
    php -S localhost:9000

[Visit our website](https://web.fe.up.pt/~up202208689/pre-loved)

## External Libraries

We have used the following external libraries:

- https://qr.bitjson.com/
- Google charts

## Screenshots
- Home Page

![Screenshot1](screenshots/img_1.png)
- Search Page

![Screenshot1](screenshots/img_2.png)
- Item Page

![Screenshot1](screenshots/img.png)
- Profile Page

![Screenshot1](screenshots/img_8.png)
- User Page

![Screenshot1](screenshots/img_3.png)
- Cart Page

![Screenshot1](screenshots/img_4.png)
- Checkout Page

![Screenshot1](screenshots/img_6.png)
- Admin Page

![Screenshot1](screenshots/img_5.png)
- Shipping form

![Screenshot1](screenshots/img_7.png)

## Implemented Features

**General**:

- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Sellers**  should be able to:

- [x] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [x] Track and manage their listed items.
- [x] Respond to inquiries from buyers regarding their items and add further information if needed.
- [x] Print shipping forms for items that have been sold.

**Buyers**  should be able to:

- [x] Browse items using filters like category, price, and condition.
- [x] Engage with sellers to ask questions or negotiate prices.
- [x] Add items to a wishlist or shopping cart.
- [x] Proceed to checkout with their shopping cart (simulate payment process).

**Admins**  should be able to:

- [x] Elevate a user to admin status.
- [x] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [x] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [x] **SQL injection**
- [x] **Cross-Site Scripting (XSS)**
- [x] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: hash_password&verify_password

**Aditional Requirements**:

We also implemented the following additional requirements (you can add more):

- [x] **Rating and Review System**
- [ ] **Promotional Features**
- [x] **Analytics Dashboard**
- [x] **Multi-Currency Support**
- [ ] **Item Swapping**
- [ ] **API Integration**
- [ ] **Dynamic Promotions**
- [ ] **User Preferences**
- [ ] **Shipping Costs**
- [ ] **Real-Time Messaging System**

> Final grade: 19.5
