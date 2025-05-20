console.log('🚀 Main process started');

const { app, BrowserWindow } = require('electron');
const path = require('path');
const { spawn } = require('child_process');
const waitOn = require('wait-on');

let mainWindow;
let laravelProcess;

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1200,
    height: 800,
    webPreferences: {
      preload: path.join(__dirname, '../preload/index.js'),
      contextIsolation: true,
      nodeIntegration: false,
    }
  });

  mainWindow.loadURL('http://127.0.0.1:8000');
  mainWindow.webContents.openDevTools(); // console dev
}

function startLaravel() {
  console.log('⚙️ Iniciando Laravel com: php artisan serve');

  laravelProcess = spawn('php', ['artisan', 'serve'], {
    cwd: path.join(__dirname, '../../'), // raiz do Laravel
    shell: true,
    detached: true,
    stdio: 'ignore'
  });

  laravelProcess.unref();
}

app.whenReady().then(() => {
  // Espera pela URL, tenta conectar — se falhar, inicia o Laravel e tenta de novo
  waitOn({ resources: ['http://127.0.0.1:8000'], timeout: 5000 }, (err) => {
    if (err) {
      startLaravel();
      console.log('⌛ Aguardando Laravel subir...');

      waitOn({ resources: ['http://127.0.0.1:8000'], timeout: 10000 }, (error) => {
        if (error) {
          console.error('❌ Laravel não subiu a tempo:', error);
          app.quit();
        } else {
          console.log('✅ Laravel iniciado. Abrindo janela...');
          createWindow();
        }
      });

    } else {
      console.log('✅ Laravel já está rodando. Abrindo janela...');
      createWindow();
    }
  });

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

// Fecha o app quando todas as janelas forem fechadas (exceto macOS)
app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});
