# Enabling and Using AI Features in Voila CMS

**Voila CMS** provides a set of AI-powered features to help you manage and optimize your content more efficiently.  

## Available Features
- **Generate SEO with AI**  
- **Generate Item Content with AI**  
- **Improve existing content**  
- **Translate content**  

## Steps to Enable and Use  

### 1. Access AI Settings
From the sidebar menu, click on **AI Content Settings & Usage** to open the settings page.  

### 2. Configuration Options
Inside the settings page, you can configure the following options:  
- **Enable/Disable AI** (On / Off)  
- **Set maximum allowed usage limit**  
- **Add your Personal OpenAI API Key**  
- **Add company information (name, description, website type)** – used during content generation.  

### 3. Monitor Usage
The settings page also includes a section to monitor **content generation requests and usage** within the CMS.  

## Important Notes
- When AI features are enabled globally in the CMS, the **SEO generation feature will be activated automatically**.  
- To use AI features inside **modules**, you must enable them manually when generating a module from its **Configuration** page:  
  - **Show Button Add By AI (TRUE/FALSE)**  
  - **Using AI Actions (TRUE/FALSE)**  
  - Both should be set to **TRUE** to enable AI actions.  

## Enabling in Code
To make sure AI features work inside your modules, add the following lines inside the `cbInit` function of:  
`AdminModuleController.php`  

```php
$this->button_add_by_ai = true;
$this->form_using_ai_actions = true;
