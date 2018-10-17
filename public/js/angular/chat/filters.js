app.filter('latestChat', function () {
    return function (input) {
        return input.slice().sort(function (a, b) {
            var a_created = (a.latest !== null ? a.latest.created_at : a.created_at);
            var b_created = (b.latest !== null ? b.latest.created_at : b.created_at);

            var ad = new Date(a_created).getTime();
            var bd = new Date(b_created).getTime();

            return (ad === bd ? 0 : (ad < bd ? 1 : -1));
        });
    };
});