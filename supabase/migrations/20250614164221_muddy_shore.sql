/*
  # Create products table for affiliate website

  1. New Tables
    - `products`
      - `id` (uuid, primary key)
      - `title` (text, product title)
      - `description` (text, product description)
      - `image_url` (text, product image URL)
      - `amazon_link` (text, affiliate link to Amazon)
      - `price` (text, optional price display)
      - `is_featured` (boolean, whether to show on homepage)
      - `display_order` (integer, order of display)
      - `created_at` (timestamp)
      - `updated_at` (timestamp)

  2. Security
    - Enable RLS on `products` table
    - Add policy for authenticated users to manage products
    - Add policy for public read access to featured products

  3. Sample Data
    - Insert existing products from the current website
*/

CREATE TABLE IF NOT EXISTS products (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  title text NOT NULL,
  description text NOT NULL,
  image_url text NOT NULL,
  amazon_link text NOT NULL,
  price text DEFAULT '',
  is_featured boolean DEFAULT true,
  display_order integer DEFAULT 0,
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

ALTER TABLE products ENABLE ROW LEVEL SECURITY;

-- Policy for authenticated users to manage products (admin access)
CREATE POLICY "Authenticated users can manage products"
  ON products
  FOR ALL
  TO authenticated
  USING (true)
  WITH CHECK (true);

-- Policy for public read access to featured products
CREATE POLICY "Public can read featured products"
  ON products
  FOR SELECT
  TO anon
  USING (is_featured = true);

-- Insert existing products from the current website
INSERT INTO products (title, description, image_url, amazon_link, display_order) VALUES
(
  'PERLETTI Men''s Summer House Slippers',
  'Ultra‑légères, boucles élégantes, confort toute la journée, semelle antidérapante – parfaites pour maison, plage et piscine, hiver comme été.',
  'https://m.media-amazon.com/images/I/61Kvp2xNiDL._AC_SY535_.jpg',
  'https://amzn.to/4e0ZigJ',
  1
),
(
  'Token for Shopping Cart One Euro 1 Coin Supermarket Shopping Iper',
  'Facilitez vos courses avec ce jeton pratique au format pièce de 1 euro, parfait pour libérer les chariots de supermarché.',
  'https://translaser.fr/cdn/shop/files/porte-cles-pour-smartphone-en-canne-de-ble-peix-1.jpg?v=1689237590',
  'https://amzn.to/43ULoYM',
  2
),
(
  'Montre Connectée Sport et Bien-être',
  'Suivez votre activité physique, votre sommeil et recevez vos notifications avec cette montre élégante et performante, votre coach personnel au poignet.',
  'https://placehold.co/400x300/a78bfa/ffffff?text=Produit+3',
  '#',
  3
);