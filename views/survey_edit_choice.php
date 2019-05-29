<div class="choice" data-choice_id="<?php if (empty($choice)) {
    echo 'CHOICE_ID';
} else {
    echo $choice->getUniqueId();
} ?>" data-choice_number="<?php if (isset($j)) {
    echo $j + 1;
} ?>">
  <label>Choice <span class="choice_number"><?php if (isset($j)) {
    echo $j + 1;
} ?></span>:</label>
  <input type="text" class="choice_text" name="choice_text[<?php if (empty($question)) {
    echo 'QUESTION_ID';
} else {
    echo $question->getUniqueId();
} ?>][<?php if (empty($choice)) {
    echo 'CHOICE_ID';
} else {
    echo $choice->getUniqueId();
} ?>]" value="<?php if (! empty($choice)) {
    echo htmlspecialchars($choice->choice_text);
} ?>" />
  <button class="delete_choice">Delete Choice</button>
</div>
