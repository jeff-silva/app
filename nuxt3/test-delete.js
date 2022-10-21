const path = require('path');
const fs = require('fs-extra');

(async () => {
  const dirNuxtBuild = path.join(__dirname, '.output', 'public');
  const dirDest = path.join(__dirname, '.laravel-public');

  const filesIgnore = ['.htaccess', 'favicon.ico', 'index.php', 'robots.txt'];
  (await fs.promises.readdir(dirDest))
    .filter(file => !filesIgnore.includes(file))
    .forEach(async (file) => {
      const fileDir = path.join('.laravel-public', file);
      await fs.promises.rm(fileDir, { recursive: true, force: true });
    });
  
  await fs.copy(dirNuxtBuild, dirDest, { overwrite: true });
  await fs.promises.rename(
    path.join(__dirname, '.laravel-public', 'index.html'),
    path.join(__dirname, '.laravel-public', 'app.html')
  );

  console.log([dirNuxtBuild, 'moved to', dirDest].join(' '));
})();