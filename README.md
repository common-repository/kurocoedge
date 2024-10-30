# KurocoEdge WordPress plugin.
This plugin allows the use of KurocoEdge with WordPress. (https://kuroco.app)

Latest Version - 1.0.2
Compatability with WordPress - 6.1  (Min 5.2)

## Functionality Roadmap
- [x] Connect with Kuroco Backend.
- [x] Manually clear Cache.
- [x] Auto Clear Cache.
- [x] Auto Sync Settings.
- [x] Manually Sync Settings.
- [ ] i18n.
- [ ] IP restriction.
- [ ] Finer IAM Control.
- [ ] Unified Admin Panel.

**Note: For WordPress, information needs to be added in `readme.txt` file. That file needs to be all small in filename.**

---
---

# Rules for Contribution.

## 1. Escape Late
- The escaping of all output variables needs to be done and that needs to be done during output time.
- DO NOT prepare the escaped variable and then print it, that is a security vulnerability. Escape the variable inline when printing.
- Based on the data that is being printed, the escaping logic should be handled.
- Reference - [WordPress documentation](https://developer.wordpress.org/plugins/security/securing-output/)

## 2. Sanatize all the Inputs
- Whenever using the `$_FILE`, `$_GET`, `$_POST` or even `$_REQUEST`, it should be sanatized.
- Based on the data that is being accepted, the sanitation should be handled.
- Reference - [WordPress documentation](https://developer.wordpress.org/plugins/security/securing-input/)

## 3. Do not use HEREDOC or NOWDOC
- They look good and make the code clean but cannot be detected properly by codesniffers.
- Therefore, they pose a security vulnerability.
- It is prohibited to use them inside WordPress.

## 4. Use Only Single Quotes
- Double quotes can be used for parsing variables. Which is okay and convinient if there are variables in the string.
- But most of the times, variables are not there. Therefore, single quote should be used.
- They help in security and a slight improvement in performance as well.

## 5. Use NONCE with API calls
- Whenever making API calls to Kuroco. Use NONCE token.
- This can help as a DG token alternative.
- All API Calls coming from WordPress should have a NONCE included.

> *Sanatize Early, Escape Late, Always Validate*

---
---

# Rules for new Release
Before creating a new release, make sure that the following things are done.
- [ ] Update the version number in PHPDOC part of `kurocoedge.php`
- [ ] Update the version number in the constant `KUROCOEDGE_VERSION` inside the file `kurocoedge.php`
- [ ] Prepare the changes for this release version.
- [ ] If this release is going to be for WordPress, then update the `readme.txt` and update changelog there as well.
- [ ] If this release is going to be for WordPress, then on the file `readme.txt` update the latest stable version.
- [ ] If this release is going to be for WordPress, then inside `tags` directory within the SVN, create a new Tag with the version number and add the code there.