#!/usr/bin/env python3

# pip install numpy
import numpy as np
# sudo apt-get install portaudio19-dev python3-all-dev
# pip install pyaudio
import pyaudio

p = pyaudio.PyAudio()

stream = p.open(
    format=pyaudio.paInt16,
    channels=1, 
    rate=44100,
    input=True,
    frames_per_buffer=1024
)

while True:
    data = np.fromstring(stream.read(1024), dtype=np.int16)

    volume = np.max(data)
    print(volume) # max: 16383

stream.stop_stream()
stream.close()
p.terminate()