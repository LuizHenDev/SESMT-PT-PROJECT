## 🚀 SISTEMA SESMT - GUIA DE INSTALAÇÃO

### Pré-requisitos
- **XAMPP** instalado (inclui Apache + MySQL + PHP 7.4+)
- **MySQL/MariaDB** executando
- Navegador moderno (Firefox, Chrome, Edge)

---

### 📋 PASSOS DE INSTALAÇÃO

#### **1. Copiar os arquivos para XAMPP**
```
1. Extrair o arquivo ZIP completo
2. Copiar a pasta 'sesmt-system' para: C:\xampp\htdocs\
   (Resultado: C:\xampp\htdocs\sesmt-system)
3. Verificar que todos os arquivos estão presentes:
   - index.php (raiz)
   - database.sql
   - config/
   - models/
   - controllers/
   - views/
   - assets/
```

#### **2. Criar banco de dados**
**Opção A: phpMyAdmin (Recomendado)**
```
1. Abrir http://localhost/phpmyadmin
2. Clicar em "+ Novo" ou "Create new database"
3. Nome do banco: sesmt_db
4. Charset: utf8mb4_unicode_ci
5. Clicar em "Create"
6. Selecionar o banco criado 'sesmt_db'
7. Ir para aba "SQL"
8. Copiar e colar TODO o conteúdo de database.sql
9. Executar (Ctrl+Enter)
```

**Opção B: Terminal/CMD**
```powershell
cd C:\xampp\mysql\bin
mysql -u root < C:\xampp\htdocs\sesmt-system\database.sql
```

#### **3. Iniciar o sistema**
```
1. Iniciar o Apache (XAMPP Control Panel)
2. Iniciar o MySQL (XAMPP Control Panel)
3. Abrir navegador: http://localhost/sesmt-system
4. Login:
   Email: admin@sesmt.com
   Senha: admin123
```

---

### 🔐 Credenciais de Teste

**Admin User:**
- Email: `admin@sesmt.com`
- Password: `admin123`

**Usuários de teste criados automaticamente na base:**
- 3 Colaboradores (Adayane Silva, Bruno Costa, Carla Rodrigues)
- 5 EPIs (Capacete, Óculos de Proteção, Luva, Bota, Colete)
- 5 Riscos (níveis baixo, médio, alto)
- 5 Treinamentos (obrigatórios e opcionais)

---

### 📁 Estrutura de Pastas

```
sesmt-system/
├── index.php                      # Roteador principal
├── database.sql                   # Schema MySQL
│
├── config/
│   ├── database.php              # Classe singleton Database
│   └── constants.php             # Constantes globais
│
├── models/                       # Classes de dados
│   ├── User.php
│   ├── Employee.php
│   ├── WorkPermit.php
│   ├── EPI.php
│   ├── EPIDelivery.php
│   ├── Risk.php
│   ├── Accident.php
│   ├── Training.php
│   └── TrainingEmployee.php
│
├── controllers/                  # Controladores CRUD
│   ├── auth.php
│   ├── users.php
│   ├── employees.php
│   ├── permits.php
│   ├── epis.php
│   ├── risks.php
│   ├── accidents.php
│   ├── training.php
│   └── dashboard.php
│
├── views/
│   ├── layouts/
│   │   ├── header.php           # Navbar + wrapper open
│   │   ├── sidebar.php          # Menu lateral
│   │   └── footer.php           # Fecha wrapper + scripts
│   │
│   ├── auth/
│   │   └── login.php            # Login page
│   │
│   ├── users/
│   │   ├── list.php
│   │   └── form.php
│   │
│   ├── employees/
│   │   ├── list.php
│   │   └── form.php
│   │
│   ├── permits/
│   │   ├── list.php
│   │   └── form.php
│   │
│   ├── epis/
│   │   ├── list.php
│   │   ├── form.php
│   │   └── delivery.php
│   │
│   ├── risks/
│   │   ├── list.php
│   │   └── form.php
│   │
│   ├── accidents/
│   │   ├── list.php
│   │   └── form.php
│   │
│   ├── training/
│   │   ├── list.php
│   │   └── form.php
│   │
│   └── dashboard/
│       └── index.php             # Main dashboard
│
├── assets/
│   ├── css/
│   │   └── style.css            # Estilos globais (Bootstrap)
│   └── js/
│       └── app.js               # Validações e helpers JS
│
└── helpers.php                  # ~55 funções utilitárias
```

---

### 🎯 Módulos Disponíveis

| Módulo | Descrição | Link |
|--------|-----------|------|
| **Dashboard** | Visão geral com gráficos Chart.js | `?page=dashboard` |
| **Colaboradores** | CRUD de funcionários | `?page=employees` |
| **PT** | Permissão de Trabalho (4 tipos) | `?page=permits` |
| **EPIs** | Equipamentos + Entregas | `?page=epis` |
| **Riscos** | Gestão de Riscos (GRO) | `?page=risks` |
| **Acidentes** | Registro + Investigação | `?page=accidents` |
| **Treinamentos** | Cursos obrigatórios/opcionais | `?page=training` |
| **Usuários** | Admin: gerenciar usuários | `?page=users` |

---

### ✨ Funcionalidades

✅ **Autenticação:** Login seguro com hashing bcrypt  
✅ **Controle de Acesso:** Papéis (Admin, Comum)  
✅ **CRUD Completo:** Create, Read, Update, Delete em todos os módulos  
✅ **Paginação:** Listas com 10 itens por página  
✅ **Validação:** Client-side + Server-side  
✅ **Dashboard:** Gráficos Chart.js (Pie, Doughnut)  
✅ **Responsivo:** Interface móvel-friendly com Bootstrap 5  
✅ **Mensagens:** Sistema de feedback (sucesso, erro, aviso)  
✅ **Segurança:** Prepared statements, Session timeout, CSRF protection  

---

### 🐛 Troubleshooting

**Problema:** "Page not found" ou "conexão recusada"
```
✓ Verificar XAMPP: Apache e MySQL estão rodando?
✓ Verificar URL: http://localhost/sesmt-system (não www)
✓ Verificar pasta: C:\xampp\htdocs\ tem a pasta sesmt-system?
```

**Problema:** "Can't connect to MySQL"
```
✓ Verificar MySQL está rodando no XAMPP Control Panel
✓ Verificar credenciais em config/database.php (default: root, sem senha)
✓ Verificar se banco 'sesmt_db' existe no phpMyAdmin
```

**Problema:** "Headers already sent"
```
✓ Remover espaços/quebras no início de config/database.php
✓ Verificar UTF-8 without BOM no arquivo
```

**Problema:** Sessão expira rapidinho
```
✓ Tempo padrão: 3600 segundos (1 hora)
✓ Alterar em config/constants.php: SESSION_TIMEOUT
```

---

### 🔧 Configurações Importantes

**Arquivo: config/constants.php**
```php
define('DEBUG_MODE', true);           // Mostrar erros em desenvolvimento
define('SESSION_TIMEOUT', 3600);      // Timeout em segundos
define('PASSWORD_MIN_LENGTH', 6);     // Mínimo de caracteres
```

**Arquivo: config/database.php**
```php
private $host = 'localhost';
private $db = 'sesmt_db';
private $user = 'root';              // Usuário MySQL
private $password = '';              // Senha MySQL (vazia por padrão)
```

---

### 📞 Suporte

**Recursos técnicos:**
- Chart.js: https://www.chartjs.org
- Bootstrap 5: https://getbootstrap.com
- Font Awesome: https://fontawesome.com
- PHP MySQLi: https://www.php.net/manual/en/mysqli.quickstart.php

---

### 📝 Changelog

**Versão 1.0.0** - Lançamento Inicial
- Sistema completo SESMT
- 8 módulos funcionais
- Dashboard com gráficos
- 100% responsivo
- 0 dependências externas (exceto CDN)

---

**Desenvolvido para XAMPP Local**  
**Sem banco de dados na nuvem, sem composer, sem build tools**  
**Pronto para copiar e rodar.**

🎉 **Bom uso do sistema!**
