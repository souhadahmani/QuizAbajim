document.addEventListener('DOMContentLoaded', function () {
    const pdfInput = document.getElementById('pdfInput');
    const pdfFileName = document.getElementById('pdfFileName');
    const generateQuizBtn = document.getElementById('generateQuizFromPDF');

    // Check if elements exist to avoid null reference errors
    if (!pdfInput || !pdfFileName || !generateQuizBtn) {
        console.error('One or more required elements are missing:', {
            pdfInput: !!pdfInput,
            pdfFileName: !!pdfFileName,
            generateQuizBtn: !!generateQuizBtn
        });
        return;
    }

    pdfInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            pdfFileName.textContent = file.name;
            generateQuizBtn.disabled = false;
        } else {
            pdfFileName.textContent = 'لم يتم اختيار ملف';
            generateQuizBtn.disabled = true;
        }
    });

    generateQuizBtn.addEventListener('click', function () {
        const file = pdfInput.files[0];
        if (file) {
            console.log('Generate Quiz button clicked, file:', file.name); // Debug log
            generateQuizFromPDF(file);
        } else {
            console.error('No file selected');
            alert('يرجى اختيار ملف PDF أولاً');
        }
    });
});

async function generateQuizFromPDF(file) {
    try {
        const generateQuizBtn = document.getElementById('generateQuizFromPDF');
        generateQuizBtn.disabled = true;
        generateQuizBtn.textContent = 'جاري إنشاء الكويز...';

        const formData = new FormData();
        formData.append('file', file);

        console.log('Sending request to generate quiz from PDF'); // Debug log

        const response = await fetch('{{ route("quiz.generateFromPDF") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Request failed:', response.status, errorText); // Debug log
            throw new Error('فشل في إنشاء الكويز من الملف: ' + errorText);
        }

        const quizData = await response.json();
        console.log('Received quiz data:', quizData); // Debug log

        if (quizData && Array.isArray(quizData) && quizData.length > 0) {
            quizData.forEach((question, index) => {
                addQuestionFromPDF(question, index);
            });
        } else {
            console.warn('No questions found in the response:', quizData); // Debug log
            alert('لم يتم العثور على أسئلة في الملف. حاول ملفًا آخر.');
        }
    } catch (error) {
        console.error('Error generating quiz:', error);
        alert('حدث خطأ أثناء إنشاء الكويز: ' + error.message);
    } finally {
        const generateQuizBtn = document.getElementById('generateQuizFromPDF');
        generateQuizBtn.disabled = false;
        generateQuizBtn.textContent = 'إنشاء كويز من PDF';
    }
}

function addQuestionFromPDF(questionData, index) {
    // Ensure currentPageNum and baseQuestionCount are defined
    if (typeof currentPageNum === 'undefined') {
        window.currentPageNum = document.querySelectorAll('#question-tabs .nav-item').length;
    }
    if (typeof baseQuestionCount === 'undefined') {
        window.baseQuestionCount = document.querySelectorAll('#question-tabs .nav-item').length;
    }

    currentPageNum++;
    const newPageNum = currentPageNum;
    const formQuestionIndex = newPageNum - baseQuestionCount - 1;

    const template = questionData.question_type === 'true_false' ? 'true-false' : 'multiple-choice';
    const questionType = template === 'multiple-choice' ? 'multiple' : 'descriptive';
    const multipleChoiceDisplay = template === 'multiple-choice' ? '' : 'style="display: none;"';
    const trueFalseDisplay = template === 'true-false' ? '' : 'style="display: none;"';

    const newTab = `
        <li class="nav-item" id="tab-${newPageNum}">
            <a class="nav-link" data-toggle="tab" href="#page-${newPageNum}">السؤال ${newPageNum} <i class="fas fa-times" onclick="deleteTab(${newPageNum})"></i></a>
        </li>`;
    document.getElementById('question-tabs').insertAdjacentHTML('beforeend', newTab);

    const newPage = `
        <div class="tab-pane quiz-box" id="page-${newPageNum}">
            <h2 class="text-center">السؤال ${newPageNum} <i class="fas fa-pen"></i></h2>
            <div class="form-group">
                <label for="question-text-${newPageNum}">نص السؤال:</label>
                <div id="question-editor-${newPageNum}">${questionData.question_text}</div>
                <input type="hidden" name="questions[${formQuestionIndex}][question_text]" id="question-text-${newPageNum}" class="form-control" value="${questionData.question_text}" required>
                <div class="invalid-feedback">يرجى إدخال نص السؤال.</div>
            </div>
            <div class="form-group">
                <label for="question-type-${newPageNum}">نوع السؤال:</label>
                <select id="question-type-${newPageNum}" class="form-control" name="questions[${formQuestionIndex}][type]" onchange="handleQuestionTypeChange(this, ${newPageNum})">
                    <option value="multiple" ${template === 'multiple-choice' ? 'selected' : ''}>اختيار متعدد</option>
                    <option value="descriptive" ${template === 'true-false' ? 'selected' : ''}>صح/خطأ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="grade-${newPageNum}">🎯 درجة السؤال:</label>
                <input type="number" name="questions[${formQuestionIndex}][grade]" id="grade-${newPageNum}" class="form-control" value="10" min="1" required>
                <div class="invalid-feedback">يرجى إدخال درجة السؤال (رقم أكبر من 0).</div>
            </div>
            <div id="multiple-choice-section-${newPageNum}" ${multipleChoiceDisplay}>
                <div class="answer-options" id="answer-options-${newPageNum}">
                    ${template === 'multiple-choice' ? questionData.options.map((option, idx) => `
                        <div class="answer-option-row">
                            <div class="answer-option-col">
                                <div class="answer-option">
                                    <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                    <input type="text" name="questions[${formQuestionIndex}][answers][${idx}]" placeholder="إجابة ${idx + 1}" class="form-control answer-input" data-index="${idx}" value="${option}" required>
                                    <label>
                                        <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="${idx}" class="correct-answer-radio" ${idx === questionData.correct_answer ? 'checked' : ''} required>
                                        صحيح
                                    </label>
                                </div>
                                <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة ${idx + 1}.</div>
                            </div>
                        </div>
                    `).join('') : ''}
                </div>
                <div class="invalid-feedback d-block answers-error" style="display: none;">يرجى إدخال إجابتين على الأقل.</div>
                <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
            </div>
            <div id="true-false-section-${newPageNum}" ${trueFalseDisplay}>
                <div class="answer-options">
                    <div class="answer-option-row">
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <span>صح</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][0]" value="صح">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="0" class="correct-answer-radio" ${questionData.correct_answer === 0 ? 'checked' : ''} required>
                                    صحيح
                                </label>
                            </div>
                        </div>
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <span>خطأ</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][1]" value="خطأ">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="1" class="correct-answer-radio" ${questionData.correct_answer === 1 ? 'checked' : ''}>
                                    صحيح
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-preview w-50 mr-2" onclick="previewQuestion(${newPageNum})">👁 معاينة السؤال</button>
                <button type="button" class="btn btn-duplicate w-50" onclick="duplicateQuestion(${newPageNum})"><i class="fas fa-copy"></i> تكرار السؤال</button>
            </div>
        </div>`;

    document.getElementById('pages-container').insertAdjacentHTML('beforeend', newPage);

    // Initialize Quill editor
    if (typeof Quill !== 'undefined') {
        quillEditors[newPageNum] = new Quill(`#question-editor-${newPageNum}`, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            }
        });
        quillEditors[newPageNum].on('text-change', function () {
            document.getElementById(`question-text-${newPageNum}`).value = quillEditors[newPageNum].root.innerHTML;
        });
    } else {
        console.error('Quill is not defined. Ensure Quill library is loaded.');
    }

    document.getElementById('question-count').textContent = currentPageNum;
    updateProgressBar();
    $(`#tab-${newPageNum} a`).tab('show');
}