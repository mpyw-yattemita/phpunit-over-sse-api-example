<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>phpunit-over-sse-api-example</title>
    </head>
    <body>
        <button id="run-btn">Run Test</button>
        <h1>Status</h1>
        <span id="status">Not Running</span>
        <hr>
        <h1>Stdout</h1>
        <textarea id="stdout" style="width: 800px; height: 300px;"></textarea>
        <h1>Stderr</h1>
        <textarea id="stderr" style="width: 800px; height: 100px;"></textarea>
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"
            ></script>
        <script>
            $('#run-btn').click(() => {
                const events = new EventSource("/api/phpunit/test", { withCredentials: true });
                events.addEventListener('phpunit:start', () => {
                    $('#status').text('In Progress');
                    $('#stdout').val('');
                    $('#stderr').val('');
                });
                events.addEventListener('phpunit:stdout', (e) => {
                    const { output } = JSON.parse(e.data);
                    $('#stdout').val(output);
                });
                events.addEventListener('phpunit:stderr', (e) => {
                    const { output } = JSON.parse(e.data);
                    $('#stderr').val(output);
                });
                events.addEventListener('phpunit:error', (e) => {
                    console.error(JSON.parse(e.data));
                    $('#status').text('Error');
                    events.close();
                });
                events.addEventListener('phpunit:done', () => {
                    $('#status').text('Done');
                    events.close();
                });
            });
        </script>
    </body>
</html>
