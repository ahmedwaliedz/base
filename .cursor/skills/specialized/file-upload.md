# File Upload Skill

## Purpose
Handle file uploads securely and consistently.

---

## Process

### Validation
- Use Form Request
- Validate:
  - file type
  - file size
  - mime type

---

### Storage
- Use Laravel storage system
- Store in appropriate disk
- Avoid storing in public root directly

---

### Naming
- Use unique file names
- Avoid collisions

---

### Security
- Do not trust file extension only
- Validate mime type
- Prevent executable uploads

---

### Database
- Store file path only
- Do not store full file data

---

### Access
- Control access via routes if needed
- Avoid exposing sensitive files publicly

---

## Output Format
- Validation rules
- Storage logic
- File naming
- DB structure
- Access strategy

## Cleanup Strategy

- Define how files are handled on:
  - update
  - delete
- Avoid orphan files in storage
