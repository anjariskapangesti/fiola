import os

search_path = "/Users/mac/Downloads/fiola2/resources/views/website/pages"
target_string = """if (data == 'created') {
                                return `<span class="badge bg-warning">Waiting Manager Approval</span>`;
                            } else if (data == 'Manager Approve') {
                                return `<span class="badge bg-warning">Waiting ITD Approve</span>`;
                            } else if (data == 'IT Approve') {
                                return `<span class="badge bg-warning">Waiting ITD MGR Approve</span>`;
                            } else if (data == 'IT MGR Approve') {
                                return `<span class="badge bg-warning">Waiting Execution</span>`;
                            } else if (data == 'On Progress') {
                                return `<span class="badge bg-info">On Progress</span>`;
                            } else if (data == 'Finished') {
                                return `<span class="badge bg-success">Finished</span>`;
                            } else {
                                return `<span class="badge bg-danger">${data}</span>`;
                            }"""

replacement_string = """if (data == 'created') {
                                return `<span class="badge bg-warning">Waiting Manager Approval</span>`;
                            } else if (data == 'Waiting Director Approval' || data == 'Manager Approve') {
                                return `<span class="badge bg-warning">Waiting Director Approval</span>`;
                            } else if (data == 'Director Approve' || data == 'Finished') {
                                return `<span class="badge bg-success">Finished</span>`;
                            } else if (data == 'Waiting Target Response') {
                                return `<span class="badge bg-info">Waiting Target Response</span>`;
                            } else {
                                return `<span class="badge bg-danger">${data}</span>`;
                            }"""

for root, dirs, files in os.walk(search_path):
    for file in files:
        if file.endswith(".blade.php"):
            file_path = os.path.join(root, file)
            with open(file_path, 'r') as f:
                content = f.read()
            
            if target_string in content:
                new_content = content.replace(target_string, replacement_string)
                with open(file_path, 'w') as f:
                    f.write(new_content)
                print(f"Updated: {file_path}")
