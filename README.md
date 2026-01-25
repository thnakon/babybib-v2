# 🖋️ ScribeHub: The Ultimate AI-Powered Research Ecosystem

ScribeHub is a premium, all-in-one platform designed for modern researchers, academics, and students. It seamlessly integrates reference management, collaborative project tools, and advanced AI intelligence to streamline the entire research lifecycle—from discovery to manuscript.

![ScribeHub Banner](https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&q=80&w=1200)

## 🚀 Core Features

### 🧠 1. AI Research Agent
*   **Context-Aware Chat**: Talk to your research library. Ask questions like *"What are the common themes in my latest papers?"* or *"Summarize my references on AI ethics."*
*   **Academic Paraphraser**: Refine your writing into peer-review quality prose with various specialized tones.
*   **Intelligent Insights**: Extract insights and synthesize information across multiple references instantly.

### 📚 2. Advanced Reference Library
*   **Smart Discovery**: Add references instantly using DOI, ISBN, or BibTeX files.
*   **Project-Based Organization**: Group your sources by research project or specific folders.
*   **Metadata Management**: Complete control over citation details, abstracts, and personal notes.

### 🤝 3. Collaboration Workspace (ClickUp Style)
*   **Team Projects**: Creating shared workspaces for labs and study groups.
*   **Task Management**: Assign tasks, set due dates, and track research progress with a sleek, interactive UI.
*   **File Sharing & Discussion**: Centralized hub for project files and academic discussions.

### ✍️ 4. Integrated Research Editor
*   **Bi-Directional View**: Write your manuscript while viewing your source PDFs in a single split-screen view.
*   **AI-Assisted Writing**: Use the built-in AI tools to rephrase or generate drafts directly within the editor.
*   **Auto-Save & Versioning**: Never lose a word with real-time manuscript syncing.

## 🎨 Design Philosophy
ScribeHub is built with a **Premium & Rich Aesthetic**:
*   **Glassmorphic Interface**: High-end visual depth using modern blur and transparency effects.
*   **Dynamic Dark Mode**: A sleek, focused environment tailored for long research hours.
*   **Bilingual Support**: Full professional localization for English and Thai.

## 🛠️ Technology Stack
*   **Frontend**: React + Inertia.js + TailwindCSS + Tiptap Editor
*   **Backend**: Laravel 11 (PHP)
*   **AI Engine**: Google Gemini 1.5/2.0 Integration
*   **Database**: PostgreSQL / MySQL
*   **Storage**: AWS S3 / Local Disk

## 📦 Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/thnakon/scribehub.git
    cd scribehub
    ```

2.  **Environment Setup**
    ```bash
    cp .env.example .env
    # Configure your database and GEMINI_API_KEY
    ```

3.  **Backend Setup**
    ```bash
    composer install
    php artisan key:generate
    php artisan migrate
    ```

4.  **Frontend Setup**
    ```bash
    npm install
    npm run dev
    ```

5.  **Go Live**
    ```bash
    php artisan serve
    ```

## 📄 License
Licensed under the [MIT License](LICENSE).

---
*Built with ❤️ for the global research community by [thnakon](https://github.com/thnakon).*
