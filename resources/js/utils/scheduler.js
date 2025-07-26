// utils/scheduler.js

export function generateSlots(start, end) {
    const result = []
    let [hh, mm] = start.split(':').map(Number)
    const [EH, EM] = end.split(':').map(Number)
    while (hh < EH || (hh === EH && mm < EM)) {
        result.push(`${String(hh).padStart(2, '0')}:${String(mm).padStart(2, '0')}`)
        mm += 15
        if (mm >= 60) {
            hh++
            mm = 0
        }
    }
    return result
}

export function isTimeValid(time, selectedDay, duration, schedule, takenTimes, parseLocalDate, dayOfWeekToKey, generateSlots) {
    if (!selectedDay || !duration) return false

    const d = parseLocalDate(selectedDay)
    const key = dayOfWeekToKey(d.getDay())
    if (!key || !schedule[key]) return false

    const allSlots = generateSlots(schedule[key].start, schedule[key].end)
    const index = allSlots.indexOf(time)

    const requiredSlots = Math.ceil(duration / 15)
    const selectedSequence = allSlots.slice(index, index + requiredSlots)

    return (
        selectedSequence.length === requiredSlots &&
        selectedSequence.every(t => !takenTimes.includes(t))
    )
}




