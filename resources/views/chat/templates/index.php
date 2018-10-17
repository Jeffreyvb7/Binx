<h2>Chat</h2>
<hr>
<div class="row" style="height: 70vh; min-height: 500px;">
    <div class="col-md-3" style="max-height: 100%;">
        <ul style="list-style: none; padding: 0; margin: 0; height: 100%; border-right: 1px solid rgba(0, 0, 0, .15); overflow-y: scroll;">
            <li ng-repeat="chat in chats | latestChat" ng-click="selectChat(chat.id)" style="display: block; border-bottom: 1px solid rgba(0, 0, 0, .1); width: 100%; height: 50px; padding-bottom: 10px; cursor: pointer;">
                <b style="font-size: 1.3em;">{{ chat.name }}</b><br>
                <small ng-bind-html="'<b>' + chat.latest.user.first_name + ' ' + chat.latest.user.last_name + ':</b> ' +  chat.latest.message | trust" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 95%;"></small>
            </li>
        </ul>
    </div>
    <chat id="{{ selectedChat }}" class="col-md-9" ng-show="selectedChat != null"></chat>
    <div class="col-md-9" ng-show="selectedChat == null">
        <h2>Select a chat on the left!</h2>
    </div>
</div>