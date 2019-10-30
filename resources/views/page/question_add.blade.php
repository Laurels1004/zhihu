{{--<script type="text/ng-template" id="question.add.tpl">--}}
    <div ng-controller="QuestionAddController" class="question-add container">
        <div class="card">
            <form name="question_add_form" ng-submit="Question.add()">
                <div class="input-group">
                    <lable>提问标题</lable>
                    {{--这里的ng-model与上面的question.new_question.title调用的是同一个数据--}}
                    <input type="text" name="title" ng-model="Question.new_question.title" required minlength="5" maxlength="255">
                </div>
                <div class="input-group">
                    <lable>提问描述</lable>
                    <textarea type="text" name="desc" ng-model="Question.new_question.desc">
                    </textarea>
                </div>
                <div class="input-group">
                    {{--<button ng-disabled="question_add_form.title.$error.required" type="submit" class="primary">提交</button>--}}
                    <button ng-disabled="question_add_form.$invalid" type="submit" class="primary">提交</button>
                </div>
            </form>
        </div>
    </div>
{{--</script>--}}
