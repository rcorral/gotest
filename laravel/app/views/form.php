<h2>Questions</h2>

	<button class="add-question">Add new question</button>

	<div id="questions-wrapper">
				<div class="question-wrapper">
	<h3>Multiple choice single answer<button class="remove-question">Remove</button></h3>
	<input type="hidden" name="questions[1][type_id]" value="1" />
	<label for="question-1">Question: </label>
	<input type="text" name="questions[1][question]" value="What is my first name?" id="question-1" />
	<label for="seconds-1">Seconds: </label>
	<input type="text" name="questions[1][seconds]" value="10" id="seconds-1" />
	<div clas="clr"></div>
	Media:
	<input type="text" name="questions[1][media]" value="" id="media-1" />
	<label>
		<input type="radio" name="questions[1][media_type]" value="link"  />
		Link
	</label>
	<label>
		<input type="radio" name="questions[1][media_type]" value="image"  />
		Image
	</label>
	<label>
		<input type="radio" name="questions[1][media_type]" value="youtube"  />
		YouTube
	</label>
	<div clas="clr"></div>
	Answers:
	<div clas="clr"></div>
	<div class="answers">
		<table a:count="3">
			<thead>
				<tr>
					<th>Answer</th>
					<th>Correct Answer</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td>
						<input type="text" name="questions[1][options][1]" value="Pepe" class="input-increment clear-input" />
					</td>
					<td>
						<input type="radio" name="questions[1][answers][]" value="1" class="val-auto-increment"  />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="text" name="questions[1][options][2]" value="Rafael" class="input-increment clear-input" />
					</td>
					<td>
						<input type="radio" name="questions[1][answers][]" value="2" class="val-auto-increment" checked="checked" />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="text" name="questions[1][options][3]" value="Jose" class="input-increment clear-input" />
					</td>
					<td>
						<input type="radio" name="questions[1][answers][]" value="3" class="val-auto-increment"  />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>			<div class="question-wrapper">
	<h3>Fill in the blank<button class="remove-question">Remove</button></h3>
	<input type="hidden" name="questions[2][type_id]" value="3" />
	<label for="question-2">Question: </label>
	<input type="text" name="questions[2][question]" value="What is my favorite color?" id="question-2" />
	<label for="seconds-2">Seconds: </label>
	<input type="text" name="questions[2][seconds]" value="0" id="seconds-2" />
	<div clas="clr"></div>
	Media:
	<input type="text" name="questions[2][media]" value="" id="media-2" />
	<label>
		<input type="radio" name="questions[2][media_type]" value="link"  />
		Link
	</label>
	<label>
		<input type="radio" name="questions[2][media_type]" value="image"  />
		Image
	</label>
	<label>
		<input type="radio" name="questions[2][media_type]" value="youtube"  />
		YouTube
	</label>
	<div clas="clr"></div>
	Answers:
	<div clas="clr"></div>
	<div class="answers">
		<table a:count="1">
			<thead>
				<tr>
					<th>Answer</th>
					<th>Correct Answer</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td>
						<input type="text" name="questions[2][options][1]" value="blue" class="input-increment clear-input" />
					</td>
					<td>
						<input type="radio" name="questions[2][answers][]" value="1" class="val-auto-increment" checked="checked" />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>			<div class="question-wrapper">
	<h3>Fill in the blank<button class="remove-question">Remove</button></h3>
	<input type="hidden" name="questions[3][type_id]" value="3" />
	<label for="question-3">Question: </label>
	<input type="text" name="questions[3][question]" value="What is your favorite color?" id="question-3" />
	<label for="seconds-3">Seconds: </label>
	<input type="text" name="questions[3][seconds]" value="0" id="seconds-3" />
	<div clas="clr"></div>
	Media:
	<input type="text" name="questions[3][media]" value="" id="media-3" />
	<label>
		<input type="radio" name="questions[3][media_type]" value="link"  />
		Link
	</label>
	<label>
		<input type="radio" name="questions[3][media_type]" value="image"  />
		Image
	</label>
	<label>
		<input type="radio" name="questions[3][media_type]" value="youtube"  />
		YouTube
	</label>
	<div clas="clr"></div>
	Answers:
	<div clas="clr"></div>
	<div class="answers">
		<table a:count="0">
			<thead>
				<tr>
					<th>Answer</th>
					<th>Correct Answer</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>			<div class="question-wrapper">
	<h3>Multiple choice multiple answer<button class="remove-question">Remove</button></h3>
	<input type="hidden" name="questions[4][type_id]" value="2" />
	<label for="question-4">Question: </label>
	<input type="text" name="questions[4][question]" value="Which two are real countries?" id="question-4" />
	<label for="seconds-4">Seconds: </label>
	<input type="text" name="questions[4][seconds]" value="20" id="seconds-4" />
	<label for="min-answers-4">Minimum Answers: </label>
	<input type="text" name="questions[4][min_answers]" value="2" id="min-answers-4" />
	<div clas="clr"></div>
	Media:
	<input type="text" name="questions[4][media]" value="" id="media-4" />
	<label>
		<input type="radio" name="questions[4][media_type]" value="link" checked="checked" />
		Link
	</label>
	<label>
		<input type="radio" name="questions[4][media_type]" value="image"  />
		Image
	</label>
	<label>
		<input type="radio" name="questions[4][media_type]" value="youtube"  />
		YouTube
	</label>
	<div clas="clr"></div>
	Answers:
	<div clas="clr"></div>
	<div class="answers">
		<table a:count="4">
			<thead>
				<tr>
					<th>Answer</th>
					<th>Correct Answer</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				
				<tr>
					<td>
						<input type="text" name="questions[4][options][1]" value="asdf" class="input-increment clear-input" />
					</td>
					<td>
						<input type="checkbox" name="questions[4][answers][]" value="1" class="val-auto-increment"  />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="text" name="questions[4][options][2]" value="France" class="input-increment clear-input" />
					</td>
					<td>
						<input type="checkbox" name="questions[4][answers][]" value="2" class="val-auto-increment" checked="checked" />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="text" name="questions[4][options][3]" value="asdgdfh" class="input-increment clear-input" />
					</td>
					<td>
						<input type="checkbox" name="questions[4][answers][]" value="3" class="val-auto-increment"  />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="text" name="questions[4][options][4]" value="South Africa" class="input-increment clear-input" />
					</td>
					<td>
						<input type="checkbox" name="questions[4][answers][]" value="4" class="val-auto-increment" checked="checked" />
					</td>
					<td>
						<button class="add-new-answer">Add</button>
					</td>
					<td>
						<button class="remove-answer">Remove</button>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>