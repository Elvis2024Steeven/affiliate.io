# Steeven Recommends - Affiliate Website

A modern affiliate marketing website with a complete admin panel system for managing products dynamically.

## Features

### Frontend
- **Modern Design**: Clean, responsive design with Tailwind CSS
- **Dynamic Product Loading**: Products are loaded from Supabase database via API
- **Mobile Responsive**: Optimized for all device sizes
- **Performance Optimized**: Fast loading with proper caching

### Admin Panel
- **Product Management**: Full CRUD operations for products
- **User-Friendly Interface**: Clean admin interface matching the frontend design
- **Real-time Updates**: Changes reflect immediately on the frontend
- **Image Management**: Support for external image URLs
- **Featured Products**: Control which products appear on homepage

### Backend
- **PHP Backend**: Robust PHP backend for admin operations
- **Supabase Integration**: Modern database with real-time capabilities
- **RESTful API**: Clean API endpoints for frontend communication
- **Security**: Row Level Security (RLS) and proper authentication

## Setup Instructions

### 1. Database Setup
1. Click the "Connect to Supabase" button in the top right to set up your Supabase project
2. The database migration will create the products table automatically
3. Sample products will be inserted to get you started

### 2. Admin Panel Access
1. Navigate to `/admin/` to access the admin panel
2. Configure authentication in `/admin/.htaccess` for security
3. Start adding your affiliate products!

### 3. Environment Variables
The following environment variables are automatically configured when you connect to Supabase:
- `VITE_SUPABASE_URL`
- `VITE_SUPABASE_ANON_KEY`
- `SUPABASE_SERVICE_ROLE_KEY`

## File Structure

```
/
├── index.html              # Main website
├── admin/
│   ├── index.php          # Admin panel interface
│   ├── config/
│   │   └── database.php   # Database configuration
│   └── .htaccess          # Admin security
├── api/
│   └── products.php       # Products API endpoint
├── supabase/
│   └── migrations/
│       └── create_products_table.sql
└── .htaccess              # URL rewriting and security
```

## Database Schema

### Products Table
- `id` (UUID): Primary key
- `title` (TEXT): Product title
- `description` (TEXT): Product description
- `image_url` (TEXT): Product image URL
- `amazon_link` (TEXT): Affiliate link
- `price` (TEXT): Optional price display
- `is_featured` (BOOLEAN): Show on homepage
- `display_order` (INTEGER): Display order
- `created_at` (TIMESTAMP): Creation date
- `updated_at` (TIMESTAMP): Last update

## Security Features

- **Row Level Security (RLS)**: Database-level security
- **Admin Authentication**: Protected admin area
- **Input Sanitization**: All user inputs are properly sanitized
- **CORS Headers**: Proper cross-origin resource sharing
- **Security Headers**: XSS protection and content type validation

## Usage

### Adding Products
1. Go to `/admin/`
2. Fill in the product form:
   - **Title**: Product name
   - **Description**: Detailed product description
   - **Image URL**: Link to product image
   - **Amazon Link**: Your affiliate link
   - **Price**: Optional price display
   - **Display Order**: Order on homepage
   - **Featured**: Check to show on homepage

### Managing Products
- **Edit**: Click the edit icon next to any product
- **Delete**: Click the delete icon (with confirmation)
- **Reorder**: Change the display order number

## API Endpoints

### GET /api/products
Returns all featured products for the frontend.

**Response:**
```json
{
  "success": true,
  "products": [
    {
      "id": "uuid",
      "title": "Product Title",
      "description": "Product Description",
      "image_url": "https://...",
      "amazon_link": "https://amzn.to/...",
      "price": "€29.99",
      "display_order": 1
    }
  ]
}
```

## Deployment

This system works with any PHP hosting provider that supports:
- PHP 7.4+
- cURL extension
- URL rewriting (.htaccess)

Popular hosting options:
- Shared hosting (cPanel)
- VPS with Apache/Nginx
- Cloud hosting (AWS, DigitalOcean, etc.)

## Affiliate Compliance

The website includes proper affiliate disclosures as required by Amazon's affiliate program and FTC guidelines. Make sure to:

1. Keep the affiliate disclosure in the footer
2. Use proper affiliate links from your Amazon Associates account
3. Follow your local regulations for affiliate marketing

## Support

For issues or questions:
1. Check the browser console for JavaScript errors
2. Verify your Supabase connection and environment variables
3. Ensure your web server supports PHP and URL rewriting