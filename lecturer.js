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
    fetch('UpdateLecturerDetails.php', {
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



// Event listener for upload questions button
document.getElementById('uploadQuestionsBtn').addEventListener('click', () => {
const examId = document.getElementById('examId').value.trim();
const questionFile = document.getElementById('questionFile').files[0];

if (!examId || !questionFile) {
    alert('Please enter exam ID and select a question file.');
    return;
}

const formData = new FormData();
formData.append('examId', examId);
formData.append('questionFile', questionFile);

fetch('upload_questions.php', {
    method: 'POST',
    body: formData
})
.then(res => res.text())
.then(response => {
    alert(response.trim());
})
.catch(err => {
    console.error(err);
    alert('An error occurred while uploading the file.');
});
});


document.getElementById('TypeQuestionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const examId = document.getElementById('TexamId').value.trim();
    const question = document.getElementById('TquestionText').value.trim();
    const optionA = document.getElementById('ToptionA').value.trim();
    const optionB = document.getElementById('ToptionB').value.trim();
    const optionC = document.getElementById('ToptionC').value.trim();
    const optionD = document.getElementById('ToptionD').value.trim();
    const correctAnswer = document.getElementById('TcorrectAnswer').value;

    if (!examId || !question || !optionA || !optionB || !optionC || !optionD || !correctAnswer) {
        alert("Please fill out all fields.");
        return;
    }

    fetch('add_question.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            exam_id: examId,
            question: question,
            option_a: optionA,
            option_b: optionB,
            option_c: optionC,
            option_d: optionD,
            correct_answer: correctAnswer
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Question added successfully!");
            document.getElementById('TypeQuestionForm').reset();
        } else {
            alert(data.message || "Failed to add question.");
        }
    })
    .catch(error => {
        console.error('Error adding question:', error);
        alert('An error occurred while adding the question.');
    });
});


// Function to populate questions in the table
function populateQuestions(questions) {
const questionsTableBody = document.getElementById('questionsTableBody');
questionsTableBody.innerHTML = ''; // Clear existing rows
questions.forEach((question, index) => {
const row = document.createElement('tr');
row.innerHTML = `
    <td class="py-2 px-4 border-b">${question.id}</td>
    <td class="py-2 px-4 border-b">${index + 1}</td>
    <td class="py-2 px-4 border-b">${question.question}</td>
    <td class="py-2 px-4 border-b">${question.option_a}</td>
    <td class="py-2 px-4 border-b">${question.option_b}</td>
    <td class="py-2 px-4 border-b">${question.option_c}</td>
    <td class="py-2 px-4 border-b">${question.option_d}</td>
    <td class="py-2 px-4 border-b">${question.correct_answer}</td>
    <td class="py-2 px-4 border-b">
    <button class="edit-btn bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg" data-id="${question.id}">
        Edit
    </button>
    <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg" data-id="${question.id}">
        Delete
    </button>
    </td>`;
questionsTableBody.appendChild(row);
});
}

//Event listener for populate questions button
document.getElementById('populateQuestionsBtn').addEventListener('click', () => {
    const viewExamId = document.getElementById('viewExamId').value;
    if (viewExamId) {
    fetch('fetch_questions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ exam_id: viewExamId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
        populateQuestions(data.questions);
        } else {
        alert(data.message || 'No questions found.');
        }
    })
    .catch(error => {
        console.error('Error fetching questions:', error);
        alert('An error occurred while fetching questions.');
    });
    } else {
    alert('Please enter an exam ID to view questions.');
    }
});

// Edit Question Modal functionality
const editQuestionModal = document.getElementById('editQuestionModal');
const closeEditQuestionModal = document.getElementById('closeEditQuestionModal');
const cancelEditQuestion = document.getElementById('cancelEditQuestion');
const editQuestionForm = document.getElementById('editQuestionForm');
const questionsTableBody = document.getElementById('questionsTableBody');
const editQuestionText = document.getElementById('editQuestionText');
const editOptionA = document.getElementById('editOptionA');
const editOptionB = document.getElementById('editOptionB');
const editOptionC = document.getElementById('editOptionC');
const editOptionD = document.getElementById('editOptionD');
const editCorrectAnswer = document.getElementById('editCorrectAnswer');
const viewResultsBtn = document.getElementById('viewResultsBtn');
const resultExamId = document.getElementById('resultExamId');
const resultsTableContainer = document.getElementById('resultsTableContainer');
const resultsTableBody = document.getElementById('resultsTableBody');
let currentQuestionId = null; // To store the ID of the question being edited

function showResults(examId) {
    fetch('get_resultsForLecturers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ exam_id: examId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.results.length > 0) {
            resultsTableBody.innerHTML = '';

            data.results.forEach(result => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="py-3 px-4">${result.student_name}</td>
                    <td class="py-3 px-4">${result.student_idNum}</td>
                    <td class="py-3 px-4">${result.exam_id}</td>
                    <td class="py-3 px-4">${result.result_id}</td>
                    <td class="py-3 px-4">${result.score}</td>
                `;
                resultsTableBody.appendChild(row);
            });

            resultsTableContainer.classList.remove('hidden');
        } else {
            alert('No results found for this exam ID');
        }
    })
    .catch(error => {
        console.error('Error fetching results:', error);
        alert('An error occurred while fetching results.');
    });
}



// Event listener for view results button
viewResultsBtn.addEventListener('click', function() {
    const examId = resultExamId.value.trim();
    if (examId) {
        showResults(examId);
    } else {
        alert('Please enter a valid Exam ID');
    }
});

// Handle Edit button
function handleEditButtonClick(event) {
    currentQuestionId = event.target.dataset.id;

    fetch(`get_question.php?id=${currentQuestionId}`)
        .then(res => res.json())
        .then(question => {
            editQuestionText.value = question.question;
            editOptionA.value = question.option_a;
            editOptionB.value = question.option_b;
            editOptionC.value = question.option_c;
            editOptionD.value = question.option_d;
            editCorrectAnswer.value = question.correct_answer;
            editQuestionModal.style.display = 'block';
        });
}

// Handle Delete button
function handleDeleteButtonClick(event) {
    const id = event.target.dataset.id;
    const viewExamId = document.getElementById('viewExamId').value;

    if (confirm('Are you sure you want to delete this question?')) {
        fetch('delete_question.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        })
        .then(res => res.text())
        .then(response => {
            alert(response);

            // Re-fetch questions after deletion
            if (viewExamId) {
                fetch('fetch_questions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ exam_id: viewExamId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateQuestions(data.questions);
                    } else {
                        alert(data.message || 'No questions found.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                    alert('An error occurred while fetching updated questions.');
                });
            }
        });
    }
}


// Event listeners for edit and delete buttons
questionsTableBody.addEventListener('click', (event) => {
    if (event.target.classList.contains('edit-btn')) {
        handleEditButtonClick(event);
    } else if (event.target.classList.contains('delete-btn')) {
        handleDeleteButtonClick(event);
    }
});

// Handle Edit form submission
editQuestionForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new URLSearchParams();
    formData.append('id', currentQuestionId);
    formData.append('question', editQuestionText.value);
    formData.append('option_a', editOptionA.value);
    formData.append('option_b', editOptionB.value);
    formData.append('option_c', editOptionC.value);
    formData.append('option_d', editOptionD.value);
    formData.append('correct_answer', editCorrectAnswer.value);

    fetch('update_question.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(res => res.text())
    .then(response => {
        alert(response);
        editQuestionModal.style.display = 'none';

        // Re-fetch and repopulate questions to reflect the changes
        const viewExamId = document.getElementById('viewExamId').value;
        if (viewExamId) {
            fetch('fetch_questions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ exam_id: viewExamId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateQuestions(data.questions);
                } else {
                    alert(data.message || 'No questions found.');
                }
            })
            .catch(error => {
                console.error('Error fetching updated questions:', error);
                alert('An error occurred while updating the table.');
            });
        }
    });
});

// Event listener for close modal button
closeModal.addEventListener('click', () => {
    editModal.style.display = 'none';
});

// Event listener for cancel button in edit modal
cancelEdit.addEventListener('click', () => {
    editModal.style.display = 'none';
});

// Event listener for close edit question modal button
closeEditQuestionModal.addEventListener('click', () => {
    editQuestionModal.style.display = 'none';
});

// Event listener for cancel button in edit question modal
cancelEditQuestion.addEventListener('click', () => {
    editQuestionModal.style.display = 'none';
});

// Event listener for clicking outside the modal to close it
window.addEventListener('click', (event) => {
    if (event.target === editModal) {
        editModal.style.display = 'none';
    }
    if (event.target === editQuestionModal) {
        editQuestionModal.style.display = 'none';
    }
});