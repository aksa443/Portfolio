const quizData = [
    {
        question: "What is the capital of France?",
        a: "Berlin",
        b: "Madrid",
        c: "Paris",
        correct: "c"
    },
    {
        question: "Who is the President of the USA?",
        a: "Joe Biden",
        b: "Donald Trump",
        c: "Barack Obama",
        correct: "a"
    },
    {
        question: "What is 2 + 2?",
        a: "3",
        b: "4",
        c: "5",
        correct: "b"
    }
];

let currentQuiz = 0;
let score = 0;
let timeLeft = 60;
let timer;

const startScreen = document.getElementById('start-screen');
const startBtn = document.getElementById('start-btn');
const quizContainer = document.getElementById('quiz-container');
const questionEl = document.getElementById('question');
const a_text = document.getElementById('a_text');
const b_text = document.getElementById('b_text');
const c_text = document.getElementById('c_text');
const submitBtn = document.getElementById('submit');
const scoreContainer = document.getElementById('score-container');
const scoreEl = document.getElementById('score');
const timerEl = document.getElementById('timer');
const reloadBtn = document.getElementById('reload');

startBtn.addEventListener('click', () => {
    startScreen.classList.add('hide');
    quizContainer.classList.remove('hide');
    loadQuiz(); // Pastikan quiz dimuat ulang setiap kali tombol start diklik
});

function loadQuiz() {
    deselectAnswers();
    const currentQuizData = quizData[currentQuiz];
    questionEl.innerText = currentQuizData.question;
    a_text.innerText = currentQuizData.a;
    b_text.innerText = currentQuizData.b;
    c_text.innerText = currentQuizData.c;

    clearInterval(timer);
    timeLeft = 60;
    timerEl.innerText = `Time left: ${timeLeft}s`;
    timer = setInterval(updateTimer, 1000);
}

function deselectAnswers() {
    const answers = document.querySelectorAll('.answer');
    answers.forEach(answer => answer.checked = false);
}

function getSelected() {
    const answers = document.querySelectorAll('.answer');
    let answer;
    answers.forEach(ans => {
        if (ans.checked) {
            answer = ans.id;
        }
    });
    return answer;
}

function updateTimer() {
    timeLeft--;
    timerEl.innerText = `Time left: ${timeLeft}s`;
    if (timeLeft <= 0) {
        timeOut();
    }
}

function timeOut() {
    clearInterval(timer);
    nextQuestion();
}

function nextQuestion() {
    currentQuiz++;
    if (currentQuiz < quizData.length) {
        loadQuiz();
    } else {
        showScore();
    }
}

function showScore() {
    document.getElementById('quiz').classList.add('hide');
    scoreContainer.classList.remove('hide');
    scoreEl.innerText = `${score} / ${quizData.length}`;
}

submitBtn.addEventListener('click', () => {
    const answer = getSelected();
    if (answer) {
        if (answer === quizData[currentQuiz].correct) {
            score++;
        }
        clearInterval(timer);
        nextQuestion();
    }
});

reloadBtn.addEventListener('click', () => {
    score = 0;
    currentQuiz = 0;
    
    // Reset and hide the score container
    scoreContainer.classList.add('hide');
    
    // Make sure quiz container is hidden and then shown after loading the first question
    quizContainer.classList.add('hide');
    
    // Reset timer and load the first question
    clearInterval(timer);
    loadQuiz();
    
    // Show the quiz container again
    quizContainer.classList.remove('hide');
    
    // Reset quiz to the initial state
    document.getElementById('quiz').classList.remove('hide');
});