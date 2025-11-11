CREATE TABLE IF NOT EXISTS Usuarios (
    UsuarioID INT PRIMARY KEY AUTO_INCREMENT,
    Email VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    Nombre VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS Emisores (
    EmisorID INT PRIMARY KEY AUTO_INCREMENT,
    UsuarioID INT NOT NULL,
    NombreCompleto VARCHAR(255) NOT NULL,
    DocumentoIdentidad VARCHAR(50),
    Email VARCHAR(255),
    Telefono VARCHAR(50),
    Direccion VARCHAR(255),
    Ciudad VARCHAR(100),
    InformacionBancaria TEXT,
    NotaLegal TEXT,
    FOREIGN KEY (UsuarioID) REFERENCES Usuarios(UsuarioID)
);

CREATE TABLE IF NOT EXISTS Clientes (
    ClienteID INT PRIMARY KEY AUTO_INCREMENT,
    UsuarioID INT NOT NULL,
    NombreCliente VARCHAR(255) NOT NULL,
    NIT_CC VARCHAR(100),
    Direccion VARCHAR(255),
    Email VARCHAR(255),
    Telefono VARCHAR(50),
    Ciudad VARCHAR(100),
    FOREIGN KEY (UsuarioID) REFERENCES Usuarios(UsuarioID)
);

CREATE TABLE IF NOT EXISTS Cotizaciones (
    CotizacionID INT PRIMARY KEY AUTO_INCREMENT,
    UsuarioID INT NOT NULL,
    ClienteID INT NOT NULL,
    EmisorID INT NOT NULL,
    NumeroCotizacion VARCHAR(50) NOT NULL,
    FechaEmision DATE NOT NULL,
    FechaVencimiento DATE,
    Estado VARCHAR(50) DEFAULT 'Pendiente',
    Concepto TEXT NOT NULL,
    ValorTotal DECIMAL(18, 2) NOT NULL,
    Terminos TEXT,
    DatosEmisorJSON TEXT,
    DatosClienteJSON TEXT,
    FOREIGN KEY (UsuarioID) REFERENCES Usuarios(UsuarioID),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmisorID) REFERENCES Emisores(EmisorID)
);

CREATE TABLE IF NOT EXISTS CuentasDeCobro (
    CuentaID INT PRIMARY KEY AUTO_INCREMENT,
    UsuarioID INT NOT NULL,
    ClienteID INT NOT NULL,
    EmisorID INT NOT NULL,
    NumeroCuenta VARCHAR(50) NOT NULL,
    FechaEmision DATE NOT NULL,
    FechaVencimiento DATE,
    Estado VARCHAR(50) DEFAULT 'Pendiente',
    Concepto TEXT NOT NULL,
    ValorTotal DECIMAL(18, 2) NOT NULL,
    DatosEmisorJSON TEXT,
    DatosClienteJSON TEXT,
    CotizacionID INT NULL,
    FOREIGN KEY (UsuarioID) REFERENCES Usuarios(UsuarioID),
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID),
    FOREIGN KEY (EmisorID) REFERENCES Emisores(EmisorID),
    FOREIGN KEY (CotizacionID) REFERENCES Cotizaciones(CotizacionID)
);

INSERT INTO Usuarios (Email, PasswordHash, Nombre)
SELECT 'admin@demo.com', '$2y$12$MvEtSdMRqf92prWkRBtkj.nXwf2gat3a706r1U/9IZuNndNc5A9.O', 'Administrador'
WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Email = 'admin@demo.com');

INSERT INTO Emisores (UsuarioID, NombreCompleto)
SELECT UsuarioID, 'Mi Empresa' FROM Usuarios WHERE Email = 'admin@demo.com'
AND NOT EXISTS (SELECT 1 FROM Emisores WHERE UsuarioID = Usuarios.UsuarioID);
