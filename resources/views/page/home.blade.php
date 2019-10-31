<!--<script type="text/ng-template" id="home.tpl">-->
<!--{{--ng-template中必须有一个最大的元素包含其它元素--}}-->
 <div ng-controller="HomeController" class="home card container">
    <h1>最新动态</h1>
    <div class="hr"></div>
    <div class="item-set">
        {{--ng-repeat相当于for--}}
        <div ng-repeat="data in Timeline.data track by $index" class="item clearfix">
            <div ng-if="data.question_id" class="vote">
                <div ng-click="Timeline.vote({id:data.id, vote:1})" class="vote-up">👍&nbsp;[: data.upvote_count :]</div>
                <div ng-click="Timeline.vote({id:data.id, vote:2})" class="vote-down">👎&nbsp;[: data.downvote_count :]</div>
            </div>
            <div class="feed-item-content">
                <div ng-if="data.question_id" class="content-active">用户:&nbsp;[: data.user.username :]添加了回答</div>
                <div ng-if="!data.question_id" class="content-active">用户:&nbsp;[: data.user.username :]添加了提问</div>
                <div class="title">[: data.title :]</div>
                <div class="content-owner">[: data.user.username :]</div>
                <div class="content-main">[: data.desc :][: data.content :]</div>
                <div class="action-set">
                    <div class="comment">评论</div>
                </div>
                <div class="comment-block">
                    <div class="hr"></div>
                    <div class="comment-item-set">
                        <div class="rect"></div>
                        <div class="comment-item clearfix">
                            <div class="user">username</div>
                            <div class="comment-content">31231</div>
                        </div>
                        <div class="comment-item clearfix">
                            <div class="user">username</div>
                            <div class="comment-content">31231</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr"></div>
        </div>
        <div ng-if="Timeline.pending" class="tac">加载中...</div>
        <div ng-if="Timeline.no_more_data" class="tac">没有更多数据了...</div>
    </div>
 </div>
<!--</script>-->
