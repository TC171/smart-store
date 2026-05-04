import re

sql_file = 'd:\\laragon\\www\\smart-store\\smart-store.sql'
with open(sql_file, 'r', encoding='utf-8') as f:
    sql = f.read()

# Match CREATE TABLE statements
table_pattern = re.compile(r'CREATE TABLE `([^`]+)` \((.*?)\) ENGINE=', re.DOTALL)
tables = table_pattern.findall(sql)

dbml = ''
refs = ''
foreign_keys = set()

for table_name, columns_str in tables:
    dbml += f'Table {table_name} {{\n'
    
    # Process columns
    lines = columns_str.split('\n')
    for line in lines:
        line = line.strip()
        # Skip constraints, keys, empty lines
        if not line or line.startswith('PRIMARY KEY') or line.startswith('UNIQUE KEY') or line.startswith('KEY') or line.startswith('CONSTRAINT'):
            continue
        
        # Match column definitions
        col_match = re.search(r'`([^`]+)` ([a-zA-Z0-9_]+)(?:\([^)]+\))?(.*)', line)
        if col_match:
            col_name = col_match.group(1)
            col_type = col_match.group(2)
            dbml += f'  {col_name} {col_type}\n'
            
            # Simple heuristic for relationships based on column names
            if col_name.endswith('_id') and col_name != 'id':
                ref_table = col_name[:-3] + 's'  # simple pluralization
                
                # Handle special cases based on Laravel conventions
                if ref_table == 'categorys': ref_table = 'categories'
                if ref_table == 'delivery_staffs': ref_table = 'users'
                if ref_table == 'shippers': ref_table = 'users'
                if ref_table == 'delivery_users': ref_table = 'users'
                if ref_table == 'parent_categories': ref_table = 'categories'
                if ref_table == 'parents': ref_table = 'categories'
                if ref_table == 'reply_tos': ref_table = 'reviews'
                if ref_table == 'admins': ref_table = 'users'
                if ref_table == 'return_shippers': ref_table = 'users'
                
                # Add relationship to set to avoid duplicates
                foreign_keys.add((table_name, col_name, ref_table, 'id'))
                
    dbml += '}\n\n'

# Get list of valid table names to ensure we only reference existing tables
valid_tables = [t[0] for t in tables]

# Build refs
for table_name, col_name, ref_table, ref_col in foreign_keys:
    if ref_table in valid_tables:
        refs += f'Ref: {table_name}.{col_name} > {ref_table}.{ref_col}\n'

with open('d:\\laragon\\www\\smart-store\\erd.dbml', 'w', encoding='utf-8') as f:
    f.write(dbml + refs)

print(f"Successfully generated DBML with {len(tables)} tables.")
