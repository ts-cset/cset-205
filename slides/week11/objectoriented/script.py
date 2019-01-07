class Radio:
  def __init__(self):
    self.current_station = 105.7
    self.on = False
  def set_station(self, station_number):
    self.current_station = station_number
  def toggle_power(self):
    self.on = not self.on
  def get_station(self):
    return self.current_station
class Clock:
  def __init__(self):
    self.alarm = "7:30"
    self.curr_time = "8:00"
  def set_alarm(self, time):
    self.alarm = time
  def get_time(self):
    return self.curr_time
class ClockRadio(Clock, Radio):
  def __init__(self):
      Radio.__init__(self)
      Clock.__init__(self)

cr = ClockRadio()
print(cr.get_station())
print(cr.get_time())
