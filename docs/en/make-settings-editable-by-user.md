# Adding Editable Settings for a Specific Role in Voila CMS

## 📌 Overview
In Voila CMS, you can assign **specific settings pages** to certain roles so that only users with that role can **view, access, and edit** those settings.  
This allows more granular control over which users can manage system settings.

---

## 🛠 Steps to Add Editable Settings for a Role

### 1. Assign Permissions to the Role
- Go to **Role Management** and edit the selected role.  
- Grant permissions for the **Settings module**:  
  - **View**  
  - **Access**  
  - **Edit**

### 2. Add Settings Items to Sidebar Menu
- Add a **new menu item** in the sidebar for the selected role.  
- Example structure:  
  - **Settings** → `#` *(Level 1 parent menu)*  
  - Add sub-items under **Settings** for the specific settings pages you want this role to access.  

### 3. Get Settings Page Links
- To get the correct link for a settings page:  
  - Log in as **Superadmin**.  
  - Navigate to the desired settings group.  
  - Copy the URL.  

For example:  
 http://127.0.0.1:8000/admin/settings/show?group=email_setting&m=0

### 4. Assign Role to the Menu Item
- When creating the new sidebar items, make sure to **assign the selected role** in the **"Roles" field** so that only users with that role can see it.

---

## Adding Settings for a Specific Role in the CMS

### Steps:
1. Go to **Role Management** and edit the selected role.  
   - Grant permissions on the **Settings module**:  
     - View  
     - Access  
     - Edit  

2. Add new items to the sidebar menu for the selected role:  
   - Parent item: **Settings** with link `#`.  
   - Sub-items: Add links to the specific settings pages.  
     - You can copy these links by opening the settings page as **Superadmin**.  
     - Example:  
       ```
       http://127.0.0.1:8000/admin/settings/show?group=email_setting&m=0
       ```

3. When adding these items to the sidebar menu, make sure to assign the correct role in the **"Privileges" field** so only that role can see them.

---

✅ Users with the selected role will now only have access to the settings pages assigned to them via the sidebar.

