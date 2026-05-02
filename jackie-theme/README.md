# Jackie Creative Agency - WordPress Theme

A modern, premium WordPress theme for Jackie Creative Agency with WooCommerce e-commerce and Flutterwave payment integration.

## Installation

1. Upload `jackie-theme` folder to `/wp-content/themes/`
2. Activate the theme in **Appearance > Themes**
3. Install & activate **WooCommerce** plugin (for e-commerce/shop)
4. Go to **Settings > Permalinks** and click **Save Changes** (to flush rewrite rules)

## Required Pages

Create these pages in WordPress and assign the correct **Page Template** (in the Page editor sidebar):

| Page | Template |
|------|----------|
| Home | *(set as Static Front Page in Settings > Reading)* |
| About | About Us |
| Services | Services |
| Portfolio | Portfolio |
| Printing | Printing Services |
| Contact | Contact |

## Editable Content via WP Admin

### Portfolio (Portfolio menu in WP Admin)
- Add portfolio items with title, featured image, client name, project URL
- Assign Portfolio Categories (Branding, Design, Printing, Web, Social, Packaging)
- Items appear dynamically on the Portfolio page

### Service Pricing (Service Pricing menu in WP Admin)
- Add pricing packages (Starter, Professional, Enterprise, etc.)
- Set price, period, features (one per line), display order
- Set Pricing Type to "Branding / Services"
- Mark one as "Popular/Featured"
- Prices appear on the Services page

### Print Pricing (Print Pricing menu in WP Admin)
- Add print products (Flyers, Business Cards, Banners, etc.)
- Set icon, description, starting price
- Add quantity pricing in JSON format: `{"100":"$25","250":"$45","500":"$75","1000":"$120"}`
- Products and pricing table appear on the Printing Services page

## Customizer Settings

Go to **Appearance > Customize** to edit:
- **Company Info**: Phone, email, address, WhatsApp number, Google Maps
- **Social Media Links**: Facebook, Instagram, Twitter, LinkedIn, Behance, TikTok
- **Homepage Statistics**: Projects completed, happy clients, years experience
- **Business Hours**: Weekday, Saturday, Sunday hours

## E-Commerce (WooCommerce)

1. Install & activate WooCommerce
2. Add products under **Products** in WP Admin
3. Shop & Cart links automatically appear in the navbar
4. Products are styled to match the theme design

## Flutterwave Payment

1. Go to **WooCommerce > Settings > Payments**
2. Enable **Flutterwave**
3. Enter your Flutterwave API keys (test or live)
4. Customers can pay via card, bank transfer, USSD, or mobile money

## Theme Features

- Dark/light mode toggle
- Loading animation
- Scroll progress indicator
- Responsive design (all screen sizes)
- WhatsApp floating button
- Newsletter subscription
- Contact form (sends to admin email)
- Print quote request form
- Animated sections with Intersection Observer
- Portfolio filter by category
- WooCommerce product grid with theme styling
