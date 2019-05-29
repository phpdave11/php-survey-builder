<!doctype html>
<html>
<head>
  <title>Survey - <?php echo htmlspecialchars($survey->survey_name); ?></title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript" src="js/survey_form.js"></script>
</head>
<body>
  <div id="main">
    <?php $title = htmlspecialchars($survey->survey_name); ?>
    <?php $subtitle = 'Survey Response Form'; ?>
    <?php include 'public_header.php'; ?>
    <div id="site_content">
      <div id="content">
        <?php if (isset($statusMessage)): ?>
            <p class="error"><?php echo htmlspecialchars($statusMessage); ?></p>
        <?php endif; ?>
        <?php if (! empty($survey) && $survey instanceof Survey): ?>
          <form id="survey_form" action="survey_form.php" method="post">
            <input type="hidden" id="action" name="action" value="add_survey_response" />
            <input type="hidden" id="survey_id" name="survey_id" value="<?php echo htmlspecialchars($survey->survey_id); ?>" />
            <div class="input_form big widelabels">
              <h2><?php echo htmlspecialchars($survey->survey_name); ?></h2>
              <?php foreach ($survey->questions as $i => $question): ?>
              <div>
                <h4 class="question_text" data-question_id="<?php echo htmlspecialchars($question->question_id); ?>" data-question_type="<?php echo htmlspecialchars($question->question_type); ?>" data-is_required="<?php echo htmlspecialchars($question->is_required); ?>"><?php echo htmlspecialchars($question->question_text); ?></h4>
                <?php if (in_array($question->question_type, ['radio', 'checkbox'])): ?>
                <?php foreach ($question->choices as $j => $choice): ?>
                  <div>
                    <?php $question_html_id = 'choice_' . htmlspecialchars($question->question_id) . '_' . htmlspecialchars($choice->choice_id); ?>
                    <input id="<?php echo $question_html_id; ?>" type="<?php echo htmlspecialchars($question->question_type); ?>" name="question_id[<?php echo htmlspecialchars($question->question_id); ?>][]" value="<?php echo htmlspecialchars($choice->choice_text); ?>" />
                    <label for="<?php echo $question_html_id; ?>"><?php echo htmlspecialchars($choice->choice_text); ?></label>
                  </div>
                <?php endforeach; ?>
                <?php elseif ($question->question_type == 'input'): ?>
                  <input type="text" name="question_id[<?php echo htmlspecialchars($question->question_id); ?>]" value="" />
                <?php elseif ($question->question_type == 'textarea'): ?>
                  <textarea name="question_id[<?php echo htmlspecialchars($question->question_id); ?>]"></textarea>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
              <div class="submit_button">
                <button id="submitButton" name="submitButton">Submit</button>
              </div>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
