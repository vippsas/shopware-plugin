<!-- START_METADATA
---
unlisted: true
---
END_METADATA -->

# Security and Privacy Risk Identification

|                                 |                                |
|---------------------------------|--------------------------------|
| **Service Owner(s)**            | Plugins team                   |
| **Risk Owner**                  | Vivi Thi Nguyen                |
| **Service Data Classification** | `PUBLIC`                       |
| **Last Reviewed**               | 03/02/26                       |


## Service description

This is the public repository for the **Vipps/MobilePay ePayment plugin for Shopware**. The repository contains the complete plugin source code, including PHP backend services, JavaScript/Twig administration UI components, payment processing logic, configuration files, documentation, and CI/CD pipeline configuration. The plugin integrates with Vipps/MobilePay payment APIs to enable Nordic payment processing for Shopware merchants. The repository is publicly accessible on GitHub for viewing and downloading, with write access restricted to trusted maintainers from WEXO and Vipps MobilePay only.

### Data Flow Diagram (DFD)

The repository itself does not process payment or customer data. However, it contains code that merchants deploy to their Shopware instances which:

1. Accepts merchant API credentials (Client ID, Client Secret, Subscription Keys, MSN) via admin configuration
2. Communicates with Vipps/MobilePay APIs for payment operations (create, authorize, capture, refund, cancel)
3. Processes order data within the merchant's Shopware environment

The code in this repository is static until deployed. Security considerations focus on preventing exposure of secrets and ensuring code cannot be misused.


## Data dictionary

| Data | Classification | Comments |
|------|----------------|----------|
| Plugin source code | PUBLIC | PHP, JavaScript, Twig templates for payment integration; no embedded secrets. |
| Documentation and images | PUBLIC | README, user guides, screenshots showing plugin configuration and usage. |
| Configuration schemas | PUBLIC | XML files defining configuration structure; placeholders only, no actual credentials. |
| CI/CD configuration | INTERNAL | .gitlab-ci.yml references internal WEXO Docker registry and build pipelines. |
| Build scripts | INTERNAL | ci/prepare script contains reference to internal registry authentication. |


## Risk scenarios / Impact Analysis

| Risk scenario | Risk Driver | Impact – What may happen if we don't implement the controls? | Security Controls |
|---------------|-------------|--------------------------------------------------------------|-------------------|
| Sensitive information accidentally committed to repo | Confidentiality | API keys, credentials, merchant secrets, or internal infrastructure details exposed publicly; potential unauthorized API access, merchant data compromise, reputational damage. | • .gitignore configured to exclude sensitive files (vendor/, .env, IDE configs)<br>• Code review process for all commits<br>• Pre-commit hooks to scan for secrets<br>• Clear contribution guidelines prohibiting secrets in commits |
| Internal CI/CD details exposed | Confidentiality | ci/prepare and .gitlab-ci.yml reference internal WEXO registry (registry.services.wexo.dk); exposure of internal infrastructure endpoints and build processes could aid reconnaissance. | • Review CI/CD files before public release<br>• Use environment variables for sensitive CI/CD values<br>• Document that CI/CD files are for internal development use |
| Vulnerable dependencies | Integrity, Availability | composer.json defines dependencies; outdated or vulnerable packages could introduce security flaws that affect all merchants using the plugin. | • Regular dependency audits<br>• Automated security scanning in CI/CD<br>• Version pinning in composer.json<br>• Security advisory monitoring |
| Compromised maintainer account or insider threat | Integrity, Confidentiality | Only maintainers have write access; compromised maintainer credentials or malicious insider could inject code that exfiltrates merchant data, manipulates payments, or creates backdoors affecting all plugin users. | • Multi-factor authentication (MFA) required for all maintainers<br>• Mandatory peer code review (minimum 2 reviewers)<br>• Automated testing and security scanning in CI/CD<br>• Audit logging of all repository changes<br>• Code signing of official releases<br>• Regular access reviews |
| Code misuse or unauthorized fork | Integrity | Public code could be forked and modified maliciously, then distributed as "official" plugin with backdoors or payment manipulation. | • Clear licensing (proprietary license specified)<br>• Official distribution channel documented (GitHub releases)<br>• Code signing of official releases<br>• Brand protection and DMCA procedures |

