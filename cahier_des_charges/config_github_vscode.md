# Guide de Configuration Visual Studio Code & GitHub

## Sommaire
1. [Prérequis](#prérequis)
2. [Installation de Git](#installation-de-git)
3. [Configuration de Git](#configuration-de-git)
4. [Installation de VS Code](#installation-de-vs-code)
5. [Extensions utiles pour Git/GitHub](#extensions-utiles-pour-gitgithub)
6. [Connexion à GitHub depuis VS Code](#connexion-à-github-depuis-vs-code)
7. [Création ou clonage d’un dépôt](#création-ou-clonage-dun-dépôt)
8. [Commandes Git de base](#commandes-git-de-base)
9. [Utilisation de Git dans VS Code](#utilisation-de-git-dans-vs-code)
10. [Gestion des conflits](#gestion-des-conflits)
11. [Bonnes pratiques](#bonnes-pratiques)
12. [Ressources utiles](#ressources-utiles)

---

## 1. Prérequis

- Un compte GitHub ([créer un compte](https://github.com/join))
- Un ordinateur sous Linux, Windows ou macOS

---

## 2. Installation de Git

### Sous Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install git
```

### Sous Windows/Mac
- [Télécharger Git](https://git-scm.com/downloads) et suivre l’assistant d’installation.

---

## 3. Configuration de Git

```bash
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@example.com"
```

Pour vérifier la configuration :
```bash
git config --list
```

---

## 4. Installation de VS Code

- [Télécharger Visual Studio Code](https://code.visualstudio.com/)
- Installer et lancer VS Code

---

## 5. Extensions utiles pour Git/GitHub

- **GitHub Pull Requests and Issues** (officiel)
- **GitLens** (historique, blame, explorateur avancé)
- **Git Graph** (visualisation des branches)
- **Markdown All in One** (pour la documentation)

---

## 6. Connexion à GitHub depuis VS Code

1. Ouvre VS Code
2. Clique sur l’icône GitHub dans la barre latérale ou sur « Se connecter à GitHub » en bas à gauche
3. Suis les instructions pour autoriser VS Code à accéder à ton compte GitHub (généralement via un navigateur)
4. Vérifie que ton compte apparaît dans la barre d’état

---

## 7. Création ou clonage d’un dépôt

### a. Cloner un dépôt existant

- Palette de commandes (Ctrl+Shift+P) → « Git: Clone »
- Colle l’URL du dépôt GitHub
- Ouvre le dossier cloné dans VS Code

### b. Initialiser un nouveau dépôt

```bash
cd /chemin/vers/ton/projet
git init
git add .
git commit -m "Initial commit"
```
- Crée un dépôt sur GitHub (site web)
- Ajoute le remote :
```bash
git remote add origin https://github.com/ton-utilisateur/nom-du-repo.git
git branch -M main
git push -u origin main
```

---

## 8. Commandes Git de base

```bash
git status           # Voir les modifications
git add .            # Ajouter tous les fichiers modifiés
git commit -m "Message"   # Sauvegarder les modifications localement
git pull             # Récupérer les changements distants
git push             # Envoyer les changements sur GitHub
git log              # Historique des commits
```

---

## 9. Utilisation de Git dans VS Code

- Onglet « Contrôle de source » (icône branche)
    - Voir les fichiers modifiés
    - Ajouter, committer, pousser, tirer
    - Gérer les branches
- Historique et détails avec GitLens
- Résolution de conflits graphique

---

## 10. Gestion des conflits

- VS Code affiche les conflits dans les fichiers concernés
- Utilise les boutons « Accepter les deux », « Accepter le courant », etc.
- Teste toujours après résolution

---

## 11. Bonnes pratiques

- Faire des commits réguliers et explicites
- Ne jamais versionner les fichiers sensibles (.env, mots de passe)
- Utiliser un fichier `.gitignore` adapté (ex : pour Laravel)
- Toujours faire un `git pull` avant de commencer à travailler
- Documenter les étapes importantes dans un `README.md`

---

## 12. Ressources utiles

- [Documentation GitHub](https://docs.github.com/fr)
- [Documentation Git](https://git-scm.com/doc)
- [Documentation VS Code](https://code.visualstudio.com/docs)
- [Git Cheat Sheet](https://education.github.com/git-cheat-sheet-education.pdf)

---

**Auteur** : F.Rabillard & IA  
**Date** : 05/10/2025