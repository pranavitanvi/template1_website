const fs = require('fs');
let data = fs.readFileSync('data/products.json', 'utf8');
data = data.replace(/https:\/\/images\.unsplash\.com\/photo-1605100804706-24ae51f379ea\?[^\"]+/g, 'assets/images/categories/rings.jpg');
data = data.replace(/https:\/\/images\.unsplash\.com\/photo-1599643477877-650e8bfc559b\?[^\"]+/g, 'assets/images/categories/necklaces.jpg');
data = data.replace(/https:\/\/images\.unsplash\.com\/photo-1535632066927-ab7c9ab60907\?[^\"]+/g, 'assets/images/categories/earrings.jpg');
data = data.replace(/https:\/\/images\.unsplash\.com\/photo-1611591437281-460bfbe1220a\?[^\"]+/g, 'assets/images/categories/bracelets.jpg');
data = data.replace(/https:\/\/images\.unsplash\.com\/photo-1599643478524-fb2564c018a7\?[^\"]+/g, 'assets/images/hero/hero_main.jpg');
fs.writeFileSync('data/products.json', data);
console.log('done');
