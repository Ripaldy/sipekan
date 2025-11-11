import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const publicDir = path.resolve(__dirname, '../../sipekan/public');

console.log('✓ Frontend built successfully to Laravel public directory');
console.log(`📂 Location: ${publicDir}`);
console.log('\n🚀 You can now run: php artisan serve');
