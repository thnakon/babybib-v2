# 🖋️ ScribeHub: The Research Operating System

**ScribeHub** is a comprehensive, AI-powered research operating system (Research OS) designed to transform how academics, researchers, and students manage their literature and draft their masterpieces. It goes beyond simple citation management, providing an "Intelligent Desk" that integrates reading, writing, and organizing in a single, high-fidelity workspace.

---

## ✨ Key Features

### 🏢 Core Workspace
*   **Integrated Split-View Editor**: Read your source PDFs on the left while drafting your paper on the right. No more switching tabs.
*   **Magic Highlight**: Transform highlights directly into notes with full citation metadata.
*   **Focus Mode**: A distraction-free environment optimized for academic deep work.

### 📚 Reference Management
*   **Instant DOI/ISBN Import**: Fetch perfectly formatted metadata automatically from links or IDs.
*   **Global & Local Standards**: Full support for 10,000+ global styles (APA 7, MLA 9, IEEE) and verified **Thai University** citation formats.
*   **Seamless Migration**: One-click import for RIS and BibTeX files from Zotero or Mendeley.

### 🤖 Intelligence & AI
*   **AI PDF Summarizer**: Get the core hypothesis, methodology, and results of any paper instantly.
*   **Academic Tone Refiner**: Polish your English to international journal standards using our AI style engine.
*   **Knowledge Graph**: Visualize connections between your authors, citations, and themes.

### 👥 Collaboration & Sync
*   **Real-time Co-authoring**: Work together on documents and bibliographies with your research team.
*   **Cloud Library Sync**: Start on your desktop and continue seamlessly on your iPad or tablet.

---

## 🚀 Tech Stack

ScribeHub is built with a modern, high-performance stack:

- **Backend**: [Laravel 12](https://laravel.com)
- **Frontend**: [React 19](https://react.dev) with [TypeScript](https://www.typescriptlang.org/)
- **Styling**: [Tailwind CSS 4](https://tailwindcss.com) (Modern, custom theme)
- **UI Architecture**: [Inertia.js](https://inertiajs.com) for a seamless SPA experience
- **Components**: [shadcn/ui](https://ui.shadcn.com) & [Lucide Icons](https://lucide.dev)
- **Database**: MySQL (optimized for research metadata)

---

## 🛠️ Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/thnakon/babybib-v2.git
   cd babybib-v2
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Edit `.env` to configure your database settings.*

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

---

## 🌍 Language Support
ScribeHub is fully localized for:
- 🇺🇸 **English** (Academic Professional)
- 🇹🇭 **ภาษาไทย** (รองรับรูปแบบการอ้างอิงมหาวิทยาลัยชั้นนำในไทย)

---

## 🛡️ License
ScribeHub is open-sourced software licensed under the **MIT license**.

---
*Developed for researchers who want to know exactly what they are reading for.*
