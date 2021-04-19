FROM python:3.8.3


RUN groupadd -g 999 flaskuser && \
    useradd -r --create-home -u 999 -g flaskuser flaskuser
USER flaskuser
WORKDIR /home/flaskuser
ENV PATH="/home/flaskuser/.local/bin:${PATH}"


COPY --chown=flaskuser requirements.txt /home/flaskuser/requirements.txt
RUN python -m pip install --user --no-cache-dir --trusted-host pypi.python.org -r /home/flaskuser/requirements.txt
COPY --chown=flaskuser . /home/flaskuser
ADD --chown=flaskuser . /home/flaskuser/app
RUN mkdir /home/flaskuser/static/upload_keys
RUN mkdir /home/flaskuser/static/keys


RUN chmod +x /home/flaskuser/entrypoint.sh
ENTRYPOINT ["python3","app.py"]
