document.addEventListener('DOMContentLoaded', function () {
    const pdfInput = document.getElementById('pdfInput');
    const pdfFileName = document.getElementById('pdfFileName');
    const generateQuizBtn = document.getElementById('generateQuizFromPDF');
    const saveallquestion = document.getElementById('button-save-all-question');

    saveallquestion.style.display="none";
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
        const savequestion = document.getElementById('button-save-question');
        const saveallquestion = document.getElementById('button-save-all-question');

        if (file) {
            pdfFileName.textContent = file.name;
            generateQuizBtn.disabled = false;
            savequestion.style.display="none";
            saveallquestion.style.display="block";

        } else {
            pdfFileName.textContent = 'لم يتم اختيار ملف';
            generateQuizBtn.disabled = true;
        }
    });

    generateQuizBtn.addEventListener('click', function () {
        const file = pdfInput.files[0];
       
        if (file) {
            console.log('Generate Quiz button clicked, file:', file.name);
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

        console.log('Sending request to generate quiz from PDF');

        const response = await fetch(window.quizRoutes.generateFromPDF, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
   
        if (!response.ok) {
            const errorData = await response.json(); // Parse JSON error response
            console.error('Request failed:', response.status, errorData);
            throw new Error(errorData.error || 'فشل في إنشاء الكويز من الملف');
        }

        const responseData = await response.json();
        console.log('Received response:', responseData);

        // Show success message
        if (responseData.success && responseData.message) {
            alert(responseData.message);
        }

        const quizData = responseData.questions;


        if (quizData && Array.isArray(quizData) && quizData.length > 0) {
            // Clear existing tabs and pages to avoid overlap
            const questionTabs = document.getElementById('question-tabsAi');
            const pagesContainer = document.getElementById('pages-container');
            const divAi = document.getElementById('divAi');

            questionTabs.innerHTML = '';
            pagesContainer.innerHTML = '';

            // Reset currentPageNum and baseQuestionCount
            window.currentPageNum = 0;
            window.baseQuestionCount = 0;

            quizData.forEach((question, index) => {
                console.log(`Processing question ${index}:`, {
                    question_text: question.question_text,
                    correct_answer: question.correct_answer,
                    options: question.options
                });
                try {
                    totalindex = quizData.length + index; // Fix variable declaration
                    divAi.style.display = "block";
                    addQuestionFromPDFNew(question, totalindex);
                    console.log(`Successfully added question ${index}`);
                } catch (error) {
                    console.error(`Error adding question ${totalindex}:`, error);
                }
            });
            if (quizData.length > 0) {
                $(`#tab-${totalindex} a`).tab('show'); // Fix template literal syntax
            }
        } else {
            console.warn('No questions found in the response:', quizData);
            alert('لم يتم العثور على أسئلة في الملف. حاول ملفًا آخر.');
        }
    } catch (error) {
        console.error('Error generating quiz:', error);
        // Display the specific error message from the backend
        const errorMessage = error.message || 'حدث خطأ غير معروف أثناء إنشاء الكويز.';
        alert(errorMessage); // Show the error to the user
        // Optionally, update a specific element in the UI
        const errorElement = document.getElementById('errorMessage');
        if (errorElement) {
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
        }
    } finally {
        const generateQuizBtn = document.getElementById('generateQuizFromPDF');
        generateQuizBtn.disabled = false;
        generateQuizBtn.textContent = 'إنشاء كويز من PDF';
    }
}

function addQuestionFromPDF(questionData, index) {
   // console.log(questionData);
    // Increment currentPageNum for each question
    window.currentPageNum = window.currentPageNum || 0;
    window.baseQuestionCount = window.baseQuestionCount || 0;
    window.currentPageNum++;
    const newPageNum = window.currentPageNum;
    const formQuestionIndex = index +2; // Simplified index for form data newPageNum -1

    const template = questionData.question_type === 'true_false' ? 'true-false' : 'multiple-choice';
    const questionType = template === 'multiple-choice' ? 'multiple' : 'descriptive';
    const multipleChoiceDisplay = template === 'multiple-choice' ? '' : 'style="display: none;"';
    const trueFalseDisplay = template === 'true-false' ? '' : 'style="display: none;"';

    // Add the tab
    const newTab = `
        <li class="nav-item" id="tab-${formQuestionIndex}">
            <a class="nav-link ${formQuestionIndex === 1 ? 'active' : ''}" data-toggle="tab" href="#page-${formQuestionIndex}">السؤال ${formQuestionIndex} <i class="fas fa-times" onclick="deleteTab(${formQuestionIndex})"></i></a>
        </li>`;
    document.getElementById('question-tabs').insertAdjacentHTML('beforeend', newTab);
   
   
    // Add the page content
    const newPage = `
        <div class="tab-pane quiz-box ${newPageNum === 1 ? 'active' : ''}" id="page-${newPageNum}">
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
            <div class="form-group">
                <label for="media-${newPageNum}">📷 إضافة صورة أو فيديو (اختياري):</label>
                <input type="file" id="media-${newPageNum}" name="questions[${formQuestionIndex}][media]" class="form-control" accept="image/*,video/*">
                <div id="media-preview-${newPageNum}" class="mt-2"></div>
            </div>
            <div id="multiple-choice-section-${newPageNum}" ${multipleChoiceDisplay}>
                <div class="answer-options" id="answer-options-${newPageNum}">
                    ${template === 'multiple-choice' ? questionData.options.map((option, idx) => `
                     
                        <div class="answer-option-row">
                            <div class="answer-option-col">
                                <div class="answer-option">
                                    <img src="${window.assetPaths.diviseurs}" alt="icon">
                                    <input type="text" name="questions[${formQuestionIndex}][answers][${idx+2}]" placeholder="إجابة ${idx + 3}" class="form-control answer-input" data-index="${idx+2}" value="${option}" required>
                                    <label>
                                        <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="${idx+2}" class="correct-answer-radio" ${idx == questionData.correct_answer ? 'checked' : ''} required>
                                        صحيح 
                                    </label>
                                </div>
                                <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة ${idx + 3}.</div>
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
                           
                                <img src="${window.assetPaths.diviseurs}" alt="icon">
                                <span>صح</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][0]" value="صح">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="0" class="correct-answer-radio"  required>
                                    صحيح
                                </label>
                            </div>
                        </div>
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="${window.assetPaths.regle}" alt="icon">
                                <span>خطأ</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][1]" value="خطأ">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="1" class="correct-answer-radio" >
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

    document.getElementById('pages-containerAI').insertAdjacentHTML('beforeend', newPage);

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

    // Add event listener for media upload
    const mediaInput = document.getElementById(`media-${newPageNum}`);
    const mediaPreview = document.getElementById(`media-preview-${newPageNum}`);
    mediaInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            mediaPreview.innerHTML = ''; // Clear previous preview
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '200px';
                img.style.marginTop = '10px';
                mediaPreview.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.maxWidth = '200px';
                video.style.marginTop = '10px';
                mediaPreview.appendChild(video);
            }
        }
    });
    

    document.getElementById('question-count').textContent = window.currentPageNum;
    updateProgressBar();
}
function addQuestionFromPDFNew(questionData, index) {
     console.log("function addQuestionFromPDFNew " +questionData);
     // Increment currentPageNum for each question
     window.currentPageNum = window.currentPageNum || 0;
     window.baseQuestionCount = window.baseQuestionCount || 0;
     window.currentPageNum++;
     const newPageNum = window.currentPageNum;
     const formQuestionIndex = newPageNum -1; // Simplified index for form data newPageNum -1
     const template = questionData.question_type === 'true_false' ? 'true-false' : 'multiple-choice';
     const questionType = template === 'multiple-choice' ? 'multiple' : 'descriptive';
     const multipleChoiceDisplay = template === 'multiple-choice' ? '' : 'style="display: none;"';
     const trueFalseDisplay = template === 'true-false' ? '' : 'style="display: none;"';
 
     // Add the tab
     const newTab = `
         <li class="nav-item" id="tab-${newPageNum}">
             <a class="nav-link ${newPageNum === 1 ? 'active' : ''}" data-toggle="tab" href="#page-${newPageNum}">السؤال ${newPageNum} <i class="fas fa-times" onclick="deleteTab(${newPageNum})"></i></a>
         </li>`;
     document.getElementById('question-tabsAi').insertAdjacentHTML('beforeend', newTab);
    
    
     // Add the page content
     const newPage = `
         <div class="tab-pane quiz-box ${newPageNum === 1 ? 'active' : ''}" id="page-${newPageNum}">
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
             <div class="form-group">
                 <label for="media-${newPageNum}">📷 إضافة صورة أو فيديو (اختياري):</label>
                 <input type="file" id="media-${newPageNum}" name="questions[${formQuestionIndex}][media]" class="form-control" accept="image/*,video/*">
                 <div id="media-preview-${newPageNum}" class="mt-2"></div>
             </div>
             <div id="multiple-choice-section-${newPageNum}" ${multipleChoiceDisplay}>
                 <div class="answer-options" id="answer-options-${newPageNum}">
                     ${template === 'multiple-choice' ? questionData.options.map((option, idx) => `
                      
                         <div class="answer-option-row">
                             <div class="answer-option-col">
                                 <div class="answer-option">
                                     <img src="${window.assetPaths.diviseurs}" alt="icon">
                                     <input type="text" name="questions[${formQuestionIndex}][answers][${idx+2}]" placeholder="إجابة ${idx + 3}" class="form-control answer-input" data-index="${idx+2}" value="${option}" required>
                                     <label>
                                         <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="${idx+2}" class="correct-answer-radio" ${idx == questionData.correct_answer ? 'checked' : ''} required>
                                         صحيح 
                                     </label>
                                 </div>
                                 <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة ${idx + 3}.</div>
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
                            
                                 <img src="${window.assetPaths.diviseurs}" alt="icon">
                                 <span>صح</span>
                                 <input type="hidden" name="questions[${formQuestionIndex}][answers][0]" value="صح">
                                 <label>
                                     <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="0" class="correct-answer-radio"  required>
                                     صحيح
                                 </label>
                             </div>
                         </div>
                         <div class="answer-option-col">
                             <div class="answer-option">
                                 <img src="${window.assetPaths.regle}" alt="icon">
                                 <span>خطأ</span>
                                 <input type="hidden" name="questions[${formQuestionIndex}][answers][1]" value="خطأ">
                                 <label>
                                     <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="1" class="correct-answer-radio" >
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
 
     document.getElementById('pages-containerNewAI').insertAdjacentHTML('beforeend', newPage);
 
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
 
     // Add event listener for media upload
     const mediaInput = document.getElementById(`media-${newPageNum}`);
     const mediaPreview = document.getElementById(`media-preview-${newPageNum}`);
     mediaInput.addEventListener('change', function (e) {
         const file = e.target.files[0];
         if (file) {
             mediaPreview.innerHTML = ''; // Clear previous preview
             if (file.type.startsWith('image/')) {
                 const img = document.createElement('img');
                 img.src = URL.createObjectURL(file);
                 img.style.maxWidth = '200px';
                 img.style.marginTop = '10px';
                 mediaPreview.appendChild(img);
             } else if (file.type.startsWith('video/')) {
                 const video = document.createElement('video');
                 video.src = URL.createObjectURL(file);
                 video.controls = true;
                 video.style.maxWidth = '200px';
                 video.style.marginTop = '10px';
                 mediaPreview.appendChild(video);
             }
         }
     });
     
 
     document.getElementById('question-count').textContent = window.currentPageNum;
     updateProgressBar();
 }
