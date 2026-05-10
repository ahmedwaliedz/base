# Database Design Skill

When designing or updating a database schema:

## Process
1. Identify entities.
2. Identify relationships.
3. Define required fields and types.
4. Separate transactional data from derived summaries.
5. Define constraints and indexes where useful.
6. Consider future extensibility without overengineering.

## Output Format
- Entities
- Tables
- Columns
- Relationships
- Constraints
- Notes

## Laravel Standards

- Use proper migration structure.
- Use foreign keys where applicable.
- Follow naming conventions used in the project.

## Data Integrity

- Avoid storing calculated values unless necessary.
- Separate transactional and derived data.

## Relationship Integrity

- Ensure all relationships are explicitly defined.
- Avoid orphan records.
