<div align="center">
    <h1>L'atelier de Manon</h1>
</div>

<div align="center">
    <img src="src/assets/img/logo_manon.webp" alt="Logo de l'atelier de Manon" width="300">
</div>

<br>

**L'atelier de Manon**, créatrice de bijoux, se lance dans le monde de l'e-commerce avec un site conçu pour refléter son univers. Accessible sur tous les écrans, ce site mettra en avant ses créations uniques et leurs nombreuses options de personnalisation.

Les utilisateurs pourront explorer les collections, filtrer leurs recherches et commander en ligne en créant un compte. Une section administrateur permettra à Manon de gérer facilement ses produits, la page d'accueil, la FAQ, et d'accéder à l'historique des ventes pour optimiser la gestion des stocks.

Enfin, une page de contact et des liens vers ses réseaux sociaux faciliteront les échanges avec ses clients.

---

## ✨ **Fonctionnalités clés**  

- **Présentation des bijoux** : Découvrez les créations uniques de Manon avec leurs options de personnalisation.  
- **Commande en ligne** : Passez commande simplement en créant un compte.  
- **Gestion des contenus** : Mettez à jour produits, page d'accueil et FAQ sans effort.  
- **Suivi des ventes** : Consultez l'historique des commandes pour une gestion optimale des stocks.  
- **Contact et réseaux sociaux** : Connectez-vous facilement avec Manon via une page dédiée et des liens vers ses réseaux.  

---

## 🛠️ **Installation**  

> **Note** : Ce dépôt correspond à la partie **back-end** du projet.

### 1. **Cloner le dépôt**  
Récupérez le projet en local avec la commande suivante :  
```bash
git clone git@github.com:LovisCoding/atelier_manon_back.git
```

### 2. **Accéder au projet**
Déplacez-vous dans le dossier du projet :  
```bash
cd atelier_manon_back
```

### 3. **Installer les dépendances**
Installez les dépendances du projet avec la commande suivante :  
```bash
composer install
```

### 4. **Configurer CodeIgniter 4**
Copiez le fichier de configuration d'exemple :  
```bash
cp .env.example .env
```

### 5. **Configurer la base de données**
Modifiez le fichier .env en y ajoutant vos informations de connexion :
```bash
database.default.DSN =
database.default.hostname = localhost
database.default.database = votredb
database.default.username = votreuser
database.default.password = motdepasse
database.default.DBDriver = Postgre
database.default.port = 7777

FRONT_URL=http://localhost:5173

CONTACT_MAIL=mail.atelierdemanon@gmail.com
```

### 6. **Créer la base de données**
Exécutez le fichier SQL `init.sql` situé à la racine du projet pour créer manuellement les tables dans votre base de données.

### 7. **Démarrer l'application**
Lancez l'application avec la commande suivante :  
```bash
php spark serve
```
