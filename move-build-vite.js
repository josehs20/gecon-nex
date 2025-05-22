// move-assets.js
import fs from 'fs';
import path from 'path';

const oldAssetsPath = path.resolve('public/build/assets');  // onde o vite gera os assets
const newAssetsPath = path.resolve('public/build/.vite/assets');  // para onde quer mover

// Cria pasta destino se não existir
if (!fs.existsSync(newAssetsPath)) {
  fs.mkdirSync(newAssetsPath, { recursive: true });
}

// Move os arquivos de assets, exceto os que contém 'woff2'
fs.readdir(oldAssetsPath, (err, files) => {
  if (err) throw err;

  files.forEach(file => {
    if (file.includes('woff2')) {
      console.log(`Ignorado ${file}`);
      return;  // pula esse arquivo
    }

    const oldFile = path.join(oldAssetsPath, file);
    const newFile = path.join(newAssetsPath, file);

    fs.rename(oldFile, newFile, (err) => {
      if (err) throw err;
      console.log(`Movido ${file} para ${newAssetsPath}`);
    });
  });
});
