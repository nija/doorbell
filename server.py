'''Chat Engine'''
# pylint: disable=I0011,C0103

# ====== Imports ======
# Standard imports
import bleach
from datetime import datetime
import json
import os
import pprint
# Flask and related imports
from jinja2 import StrictUndefined
from flask import Flask, jsonify, render_template, redirect, request, flash, send_from_directory, session
from flask_debugtoolbar import DebugToolbarExtension



# ====== Server Start-up ======
# Create a Flask app
app = Flask(__name__)

# Set up the secret key needed for session and debug-toolbar
app.secret_key = 'BalootIsTheBestDoggie'

# Normally, if you use an undefined variable in Jinja2, it fails silently.
# This is horrible. Fix this so that, instead, it raises an error.
app.jinja_env.undefined = StrictUndefined


# ====== Routes Definitions ======

######  Flask Routes  ######
# Show homepage
@app.route('/')
def index():
    '''Home page'''

    return render_template("index.html")

@app.route('/favicon.ico')
def serve_favicon():
    '''Serve our favicon'''
    return send_from_directory(os.path.join(app.root_path, 'static'), 'favicon.ico')

@app.route('/activate/<int:pin>', methods=["POST"])
def activate(pin):
    '''Testing function: activate a pin'''
    # Code goes here
    flash(jsonify({'activate': pin}))

@app.route('/deactivate/<int:pin>', methods=["POST"])
def deactivate(pin):
    '''Testing function: activate a pin'''
    # Code goes here
    flash(jsonify({'deactivate' : pin}))


@app.route('/pulse/<int:pin>', methods=["POST"])
def pulse(pin):
    '''Testing function: activate a pin'''
    # Code goes here
    flash(jsonify({'pulse': pin}))


@app.route('/play', methods=["POST"])
def play(encoding):
    '''Testing function: activate a pin'''
    encoding = bleach.clean(encoding)
    # Code goes here
    flash(jsonify({'play': encoding}))


# ====== Main Application ======
if __name__ == "__main__":

    print "\n    HEREEEEE!\n\n"

    # DebugToolbarExtension requires debug=True before it will run correctly
    # Leave this as is because of the 'No handlers could be found for logger
    # "sqlalchemy.pool.QueuePool"' http 500 error that results when it is taken
    # out
    app.debug = True
    # app.debug = False

    # Set the port
    port = int(os.environ.get("PORT", 5002))

    # Allow more processes so there's enough wiggle room to handle multiple requests
    # Use the DebugToolbar
    # DebugToolbarExtension(app)
    app.run(host="0.0.0.0", port=port)


