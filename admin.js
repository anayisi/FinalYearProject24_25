// Edit Details Modal functionality
const editModal = document.getElementById('editModal');
const editDetailsBtn = document.getElementById('editDetailsBtn');
const closeModal = document.getElementById('closeModal');
const cancelEdit = document.getElementById('cancelEdit');

editDetailsBtn.addEventListener('click', () => {
    editModal.style.display = 'block';
    // Populate current details in the modal fields
    document.getElementById('editName').value = document.getElementById('userName').innerText;
    document.getElementById('editEmail').value = document.getElementById('userEmail').innerText;
    document.getElementById('editDob').value = document.getElementById('userDob').innerText;
});

closeModal.addEventListener('click', () => {
    editModal.style.display = 'none';
});

cancelEdit.addEventListener('click', () => {
    editModal.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === editModal) {
        editModal.style.display = 'none';
    }
});

// Form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const name = document.getElementById('editName').value;
    const email = document.getElementById('editEmail').value;
    const dob = document.getElementById('editDob').value;
    
    // Update displayed values
    document.getElementById('userName').textContent = name;
    document.getElementById('userEmail').textContent = email;
    document.getElementById('userDob').textContent = dob;
    
    //update the database with new values
    fetch('UpdateAdminDetails.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            email: email,
            dob: dob
        })
    })
    
    // Show success message
    alert('Details updated successfully!');

    // Close modal
    editModal.style.display = 'none';

});

//fetch members and ids from database when page loads
window.onload = function() {
    fetch('fetchIds.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tableBody = document.getElementById('idTableBody');
            data.ids.forEach(id => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="py-2 px-4 border-b">${id.student_id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="student" data-id="${id.student_id}"><i class="fas fa-trash"></i></button></td>
                    <td class="py-2 px-4 border-b">${id.lecturer_id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="lecturer" data-id="${id.lecturer_id}"><i class="fas fa-trash"></i></button></td>
                    <td class="py-2 px-4 border-b">${id.admin_id} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="admin" data-id="${id.admin_id}"><i class="fas fa-trash"></i></button></td>
                    `;
                tableBody.appendChild(newRow);
            });
        } else {
            alert('Failed to load IDs.');
        }
    })

    fetch('members.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const adminTable = document.getElementById('adminTableBody');
                data.administrators.forEach(admin => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">${admin.name}</td>
                        <td class="py-2 px-4 border-b">${admin.email}</td>
                        <td class="py-2 px-4 border-b">${admin.id_num}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="delete-user bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg" 
                                    data-type="administrator" data-id="${admin.admin_id}">
                                Delete
                            </button>
                        </td>`;
                    adminTable.appendChild(row);
                });

                const lecturerTable = document.getElementById('lecturerTableBody');
                data.lecturers.forEach(lecturer => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">${lecturer.name}</td>
                        <td class="py-2 px-4 border-b">${lecturer.email}</td>
                        <td class="py-2 px-4 border-b">${lecturer.school}</td>
                        <td class="py-2 px-4 border-b">${lecturer.id_num}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="delete-user bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg" 
                                    data-type="lecturer" data-id="${lecturer.lecturer_id}">
                                Delete
                            </button>
                        </td>`;
                    lecturerTable.appendChild(row);
                });

                const studentTable = document.getElementById('studentTableBody');
                data.students.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">${student.name}</td>
                        <td class="py-2 px-4 border-b">${student.level}</td>
                        <td class="py-2 px-4 border-b">${student.program}</td>
                        <td class="py-2 px-4 border-b">${student.id_num}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="delete-user bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg" 
                                    data-type="student" data-id="${student.student_id}">
                                Delete
                            </button>
                        </td>`;
                    studentTable.appendChild(row);
                });
            } else {
                alert('Failed to load member data.');
            }
        })
    
    .catch(error => console.error('Error:', error));
};

// Generate ID functionality
document.getElementById('generateIdBtn').addEventListener('click', function() {
    const idType = document.getElementById('idType').value;
    
    // Generate random alphanumeric ID
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 8; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    
    // Prefix based on type
    let prefix = '';
    if (idType === 'student') prefix = 'STU';
    else if (idType === 'lecturer') prefix = 'LEC';
    else if (idType === 'admin') prefix = 'ADM';
    
    const newId = prefix + result;
    
    // Add to table (in a real app, this would be added to the database first)
    const tableBody = document.getElementById('idTableBody');
    const newRow = document.createElement('tr');
    
    // Determine which cell to put the new ID in
    let stuCell = '<td class="py-2 px-4 border-b"></td>';
    let lecCell = '<td class="py-2 px-4 border-b"></td>';
    let admCell = '<td class="py-2 px-4 border-b"></td>';
    
    if (idType === 'student') {
        stuCell = `<td class="py-2 px-4 border-b">${newId} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="student" data-id="${newId}"><i class="fas fa-trash"></i></button></td>`;
    } else if (idType === 'lecturer') {
        lecCell = `<td class="py-2 px-4 border-b">${newId} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="lecturer" data-id="${newId}"><i class="fas fa-trash"></i></button></td>`;
    } else if (idType === 'admin') {
        admCell = `<td class="py-2 px-4 border-b">${newId} <button class="ml-2 text-red-600 hover:text-red-800 delete-id" data-type="admin" data-id="${newId}"><i class="fas fa-trash"></i></button></td>`;
    }
    
    newRow.innerHTML = `
        ${stuCell}
        ${lecCell}
        ${admCell}
    `;
    
    tableBody.appendChild(newRow);
    
    // Store the new ID in the database
    fetch('generateId.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idType: idType,
            newId: newId
        })
    })
    
    // Show success message
    alert(`${idType.charAt(0).toUpperCase() + idType.slice(1)} ID generated successfully!`);
});

// Delete ID functionality
document.addEventListener('click', function(e) {
    let button = e.target.closest('.delete-id');
    if (button) {
        const idType = button.getAttribute('data-type');
        const id = button.getAttribute('data-id');
        
        // Confirm deletion
        if (confirm(`Are you sure you want to delete ${idType.charAt(0).toUpperCase() + idType.slice(1)} ID: ${id}?`)) {
            // Remove from table (in a real app, this would also remove from the database)
            button.closest('tr').remove();
            //delete from database
            fetch('deleteId.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idType: idType,
                    id: id
                })
            })
            
            // Show success message
            alert(`${idType.charAt(0).toUpperCase() + idType.slice(1)} ID deleted successfully!`);
        }
    }
});

// Delete user from database and UI
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-user')) {
        const userType = e.target.getAttribute('data-type');
        const userId = e.target.getAttribute('data-id');

        if (confirm(`Are you sure you want to delete this ${userType}?`)) {
            fetch('delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: userType, id: userId })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    e.target.closest('tr').remove();
                    alert(`${userType} deleted successfully.`);
                } else {
                    alert(`Failed to delete ${userType}.`);
                }
            })
            .catch(error => console.error('Delete error:', error));
        }
    }
});
