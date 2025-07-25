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
    fetch('UpdateStudentDetails.php', {
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

// DOM Elements
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');
const startExamBtn = document.getElementById('startExamBtn');
const examIdInput = document.getElementById('examIdInput');
const examContent = document.getElementById('examContent');
const questionsContainer = document.getElementById('questionsContainer');
const examForm = document.getElementById('examForm');
const submitExamBtn = document.getElementById('submitExamBtn');
const instructionsModal = document.getElementById('instructionsModal');
const beginExamBtn = document.getElementById('beginExamBtn');
const examTimer = document.getElementById('examTimer');
const totalTimeDisplay = document.getElementById('totalTimeDisplay');
const resultModal = document.getElementById('resultModal');
const resultScore = document.getElementById('resultScore');
const closeResultModalBtn = document.getElementById('closeResultModalBtn');
const viewResultsBtn = document.getElementById('viewResultsBtn');
const resultExamId = document.getElementById('resultExamId');
const resultsTableContainer = document.getElementById('resultsTableContainer');
const resultsTableBody = document.getElementById('resultsTableBody');

// Current state
let examQuestions = {};
let currentExamId = null;
let currentExamTimer = null;
let examTimeLeft = 0;
let currentAdminId = null;
let examStarted = false;

function showResults(examId) {
    fetch('get_resultsForStudents.php', {
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

function startExam() {
    // Check if window is maximized
    const isMaximized = window.innerWidth >= screen.availWidth && window.innerHeight >= screen.availHeight;

    if (!isMaximized) {
        alert('Please maximize your browser window before starting the exam.');
        return;
    }

    instructionsModal.style.display = 'none';
    examStarted = true;
    startExamBtn.disabled = true;

    // Clone and shuffle questions array
    const questions = [...examQuestions[currentExamId]];
    
    for (let i = questions.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [questions[i], questions[j]] = [questions[j], questions[i]];
    }

    questionsContainer.innerHTML = '';

    questions.forEach((question, index) => {
        // Map correct_answer (A/B/C/D) to the actual text
        const optionsMap = {
            A: question.options[0],
            B: question.options[1],
            C: question.options[2],
            D: question.options[3]
        };

        // Convert correct_answer string like "AC" → ['A', 'C']
        const correctLetters = question.correct_answer.split('');

        // Map to correct texts
        const correctOptionTexts = correctLetters.map(letter => optionsMap[letter]);

        // Prepare option objects with isCorrect flag
        const optionObjects = question.options.map(opt => ({
            text: opt,
            isCorrect: correctOptionTexts.includes(opt)
        }));

        // Shuffle options
        for (let i = optionObjects.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [optionObjects[i], optionObjects[j]] = [optionObjects[j], optionObjects[i]];
        }

        // Store the correct answer texts after shuffle
        question.shuffledCorrectAnswers = optionObjects
            .filter(opt => opt.isCorrect)
            .map(opt => opt.text);

        // Detect multiple correct answers
        const isMultiple = correctLetters.length > 1;
        const inputType = isMultiple ? 'checkbox' : 'radio';

        // Render HTML
        const questionDiv = document.createElement('div');
        questionDiv.className = 'question-container';

        questionDiv.innerHTML = `
            <h4 class="font-semibold mb-2">Question ${index + 1}: ${question.text}</h4>
            <div class="space-y-2 ml-4">
                ${optionObjects.map((option, i) => `
                    <label class="flex items-center">
                        <input type="${inputType}" name="q${question.id}${isMultiple ? '[]' : ''}" value="${option.text}" class="mr-2">
                        ${String.fromCharCode(65 + i)}. ${option.text}
                    </label>
                `).join('')}
            </div>
        `;

        // Store correct answer and question ID for later grading
        questionDiv.dataset.questionId = question.id;
        questionDiv.dataset.correctAnswer = question.shuffledCorrectAnswer;

        questionsContainer.appendChild(questionDiv);
    });

    // Save the updated questions with tracked answers
    examQuestions[currentExamId] = questions;

    // Start timer
    examContent.classList.remove('hidden');
    updateTimerDisplay();
    currentExamTimer = setInterval(updateTimer, 1000);
}

function updateTimer() {
    examTimeLeft--;
    updateTimerDisplay();
    
    if (examTimeLeft <= 0) {
        submitExam();
    }
}

function updateTimerDisplay() {
    const minutes = Math.floor(examTimeLeft / 60);
    const seconds = examTimeLeft % 60;
    examTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Change color when time is running low
    if (examTimeLeft <= 10) {
        examTimer.classList.add('animate-pulse');
    } else {
        examTimer.classList.remove('animate-pulse');
    }
}

function submitExam() {
    if (!examStarted) return;

    examStarted = false;
    clearInterval(currentExamTimer);
    
    const questions = examQuestions[currentExamId];
    let correctAnswers = 0;

   questions.forEach((question) => {
        const isMultiple = question.shuffledCorrectAnswers.length > 1;
        const inputName = `q${question.id}${isMultiple ? '[]' : ''}`;

        const selectedNodes = document.querySelectorAll(`input[name="${inputName}"]:checked`);
        const selectedValues = Array.from(selectedNodes).map(input => input.value).sort();
        const correctValues = question.shuffledCorrectAnswers.slice().sort();

        // Check if selected values match the correct ones
        const isCorrect = selectedValues.length === correctValues.length &&
                        selectedValues.every((val, idx) => val === correctValues[idx]);

        if (isCorrect) {
            correctAnswers++;
        }
    });

    const score = `${correctAnswers}/${questions.length}`;

    // Send results to backend
    fetch('submit_results.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            exam_id: currentExamId,
            score: score
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Exam submitted successfully!');
        } else {
            alert('Error submitting exam: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error Sending Scores: ', err);
    });

    // Show result
    resultScore.textContent = score;
    resultModal.style.display = 'block';
    
    // Reset exam UI
    examContent.classList.add('hidden');
    examIdInput.value = '';
    startExamBtn.disabled = false; //Re-enable start button
}

function tryAutoSubmitExam(reason) {
    if (typeof examStarted !== 'undefined' && examStarted === true && typeof submitExam === 'function') {
        console.log(reason + " - submitting exam automatically.");
        submitExam(); // This should handle cleanup and submission
    }
}

function showExamInstructions(examId) {
    const questions = examQuestions[examId];
    const totalTime = questions.length * 30; // 30 seconds per question
    examTimeLeft = totalTime;
    
    // Display total time in minutes and seconds
    const minutes = Math.floor(totalTime / 60);
    const seconds = totalTime % 60;
    totalTimeDisplay.textContent = `${minutes > 0 ? minutes + ' minute' + (minutes > 1 ? 's' : '') + ' and ' : ''}${seconds} seconds`;
    
    instructionsModal.style.display = 'block';
}

startExamBtn.addEventListener('click', function () {
    if (examStarted) return;
    
    const examId = examIdInput.value.trim();

    if (!examId) {
        alert('Please enter an Exam ID.');
        return;
    }

    // Check if window is maximized
    const isMaximized = window.innerWidth >= screen.availWidth && window.innerHeight >= screen.availHeight;

    if (!isMaximized) {
        alert('Please maximize your browser window before starting the exam.');
        return;
    }

    // Step 1: Check attempt count
    fetch('check_attempts.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'exam_id=' + encodeURIComponent(examId)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'allowed') {
            // Step 2: Allowed — Fetch exam questions
            fetch(`getExamQuestions.php?exam_id=${encodeURIComponent(examId)}`)
                .then(response => response.json())
                .then(questionData => {
                    if (questionData.success && questionData.questions.length > 0) {
                        examQuestions[examId] = questionData.questions;
                        currentExamId = examId;

                        // Optionally store the current attempt number (data.attempt) for use later
                        currentAttemptNumber = data.attempt;

                        showExamInstructions(examId); // Proceed to display exam
                    } else {
                        alert('Invalid Exam ID or no questions available for this exam.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                    alert('An error occurred while loading the exam.');
                });
        } else {
            // Step 3: Not allowed — show message
            alert(data.message); // "You have already attempted this exam 3 times."
        }
    })
    .catch(error => {
        console.error('Error checking attempts:', error);
        alert('An error occurred while checking exam attempts.');
    });    
});

// Begin exam after instructions
beginExamBtn.addEventListener('click', startExam);

// Submit exam
examForm.addEventListener('submit', function(e) {
    e.preventDefault();
    submitExam();
});

// Handle tab visibility change (minimized, switched tabs)
document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
        tryAutoSubmitExam("Tab hidden or minimized");
    }
});

// Handle tab refresh or close
window.addEventListener('beforeunload', function (e) {
    tryAutoSubmitExam("Page refresh or close");
    // Prevent default to show confirmation dialog (optional)
    e.preventDefault();
    e.returnValue = '';
});

// Detect when the browser window loses focus (e.g., switches to another app)
window.addEventListener('blur', function () {
    tryAutoSubmitExam("Window lost focus (another app or taskbar)");
});

let lastWindowSize = { width: window.innerWidth, height: window.innerHeight };

window.addEventListener('resize', function () {
    const newWidth = window.innerWidth;
    const newHeight = window.innerHeight;

    // Detect significant change in window size (you can adjust the threshold)
    if (Math.abs(newWidth - lastWindowSize.width) > 100 || Math.abs(newHeight - lastWindowSize.height) > 100) {
        tryAutoSubmitExam("Window resized");
    }

    lastWindowSize = { width: newWidth, height: newHeight };
});

// Close result modal
closeResultModalBtn.addEventListener('click', function() {
    resultModal.style.display = 'none';
});

// View results
viewResultsBtn.addEventListener('click', function() {
    const examId = resultExamId.value.trim();
    if (examId) {
        showResults(examId);
    } else {
        alert('Please enter a valid Exam ID');
    }
});

// Close modal buttons
//closeModalBtn.addEventListener('click', closeEditModal);
//cancelEditBtn.addEventListener('click', closeEditModal);
window.addEventListener('click', function(event) {
    if (event.target === instructionsModal) {
        instructionsModal.style.display = 'none';
    }
    if (event.target === resultModal) {
        resultModal.style.display = 'none';
    }
});

/*/ Ensure the DOM is loaded before attaching event listener
document.addEventListener("DOMContentLoaded", function () {
const viewBtn = document.getElementById("viewResultsBtn");
if (viewBtn) {
    viewBtn.addEventListener("click", function (event) {
    console.log("View Results button clicked");
    //console.log("Event object:", event);

    // Optional: prevent default if it's a form button
    //event.preventDefault();
    
    // Additional debugging info
    //debugger; // This will pause execution if DevTools are open
    });
} else {
    console.error("View Results button not found!");
}
});*/