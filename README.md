
# Santé Plus Health

## 🩺 Description du Projet

**Santé Plus Health** est une plateforme e-santé innovante développée avec **Symfony 6.4**, conçue dans le cadre du cours de **PIDEV 3A à Esprit School of Engineering**. Elle a pour objectif de **digitaliser les services de santé en Tunisie** en facilitant l'accès aux soins et en optimisant la gestion médicale.

### Objectifs :
- Améliorer l’accessibilité aux soins pour les patients.
- Centraliser les données médicales.
- Simplifier la communication entre patients et professionnels de santé.

## 🧩 Fonctionnalités principales

- 📅 **Prise de Rendez-vous** : réservation en ligne avec des médecins.
- 👩‍⚕️ **Gestion des Patients** : création, mise à jour et consultation des dossiers médicaux.
- 📈 **Suivi Médical** : ajout de traitements, résultats de tests, images/scans.
- 🏥 **Calendrier des Chirurgies** : planification des interventions.
- 📊 **Statistiques Médicales** : visualisation de données de santé.
- 💸 **Dons** : dons de sang ou d’argent.
- 💳 **Paiement sécurisé** avec Stripe.
- 📧 **Notifications par Email** : rappels, confirmations, alertes.
- 🛍️ **Vente de Produits de Parapharmacie**.

---

## 🗂️ Table des Matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Technologies Utilisées](#technologies-utilisées)
- [Contributeurs](#contributeurs)
- [Licence](#licence)

---

## ⚙️ Installation

### Prérequis

- PHP 8.2+
- Composer
- XAMPP
- Node.js & npm (pour assets)
- VS Code (ou tout autre IDE)
- Symfony CLI (optionnel)

### Étapes

```bash
1. git clone https://github.com/TON-UTILISATEUR/sante-plus-health.git
2. cd sante-plus-health
3. composer install
4. cp .env.example .env
5. php bin/console doctrine:database:create
6. php bin/console doctrine:migrations:migrate
7. symfony server:start
```

> Optionnel : installer les assets
```bash
npm install
npm run dev
```

---

## 🚀 Utilisation

Lance le projet en local via :

```bash
symfony server:start
```

Puis accède au site via : [http://localhost:8000](http://localhost:8000)

---

## 🛠️ Technologies Utilisées

- **Backend** : Symfony 6.4, PHP 8.2, Doctrine ORM
- **Base de données** : MySQL
- **Frontend** : Twig, Bootstrap
- **Paiement** : Stripe API
- **Envoi d'emails** : Mailer Symfony
- **Hébergement** *(optionnel)* : GitHub Education

> Mots-clés : `e-santé`, `symfony`, `php`, `gestion médicale`, `Esprit School of Engineering`, `stripe`, `doctrine`, `dons`, `rendez-vous médicaux`

---

## 👨‍💻 Contributeurs

| Nom    | Module Responsable                        |
|--------|-------------------------------------------|
| **Iheb**   | Dossier Médical                        |
| **Ahmed**  | Gestion des Utilisateurs               |
| **Rawen**  | Module Don (sang + argent)             |
| **Nassime**| Rendez-vous et Consultation            |
| **Nour**   | Gestion des Produits de Parapharmacie  |

---

## 📄 Licence

Ce projet est sous licence **MIT** – voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 💡 Remerciements

Ce projet a été réalisé sous la supervision de l’équipe pédagogique de **Esprit School of Engineering** dans le cadre du module PIDEV 3A.

---

## 🏷 Topics GitHub

Tu peux ajouter ces *topics* à ton repository :

```txt
symfony php e-santé gestion-patients stripe twig esprit-school-of-engineering tunisian-healthcare
```
