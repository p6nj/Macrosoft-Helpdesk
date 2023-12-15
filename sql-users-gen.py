from xml.dom.minidom import parse

doc = parse('src/bd/test_users.xml')
for i in doc.getElementsByTagName('row'):
    child = i.childNodes
    user="`"+child[1].firstChild.data+"`@localhost";
    print(f"create user {user} identified by '"+child[3].firstChild.data+"';")
    role = '' if not child[5].firstChild else child[5].firstChild.data
    role = 'UTILISATEUR' if role=='' else 'ADMIN_WEB' if role=='Admin web' else 'ADMIN_SYS' if role=='Admin sys' else 'TECHNICIEN' if role=='Technicien' else 'VISITEUR'
    print(f"grant {role} to {user};")
    print(f"set default role {role} for {user};")