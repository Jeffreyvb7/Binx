<h1 ng-bind="chat.name">Title</h1>
<hr>
<ul style="list-style: none; margin: 0; padding: 0; width: 100%; height: calc(100% - 76px - 37px); overflow-y: scroll;">
    <li ng-repeat="message in chat.messages | orderBy:created_at" style="display: block; border-bottom: 1px solid rgba(0, 0, 0, .15); padding: 6px 0;">
        <b>{{ message.user.first_name + " " + message.user.last_name }}:</b> {{ message.message }}
    </li>
</ul>

<form method="post" class="form-inline" ng-submit="sendChat(chat.id, newMessage)">
    <input type="text" class="form-control col-md-10" placeholder="Type a message" ng-disabled="!connected" ng-model="newMessage">
    <button class="btn btn-primary form-control col-md-2" ng-disabled="!connected">Send</button>
</form>
