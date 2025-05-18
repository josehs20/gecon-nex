console.log('🚀 Main process started');

const { app, BrowserWindow } = require('electron');
const path = require('path');

let mainWindow;

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

  mainWindow.webContents.openDevTools(); // abre o console da janela
}

app.whenReady().then(() => {
  createWindow();

  app.on('activate', () => {
    // No macOS, recria janela se nenhuma estiver aberta
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

// Fecha o app quando todas as janelas forem fechadas (exceto macOS)
app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});
