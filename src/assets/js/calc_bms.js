function SetUpSemesters(semesters, subjects, notes) {
    var response = JSON.parse(semesters);
    if (response != null) {
        for (var smes in response) {
            var li = document.createElement("li");
            li.innerHTML = response[smes].semesterTag;
            li.setAttribute("semesterPK", response[smes].id);
            li.addEventListener("click", function () {
                document.getElementById("table-notes-norm").innerHTML = "";
                document.getElementById("table-notes-end").innerHTML = "";
                calcBMS(subjects, notes, this.getAttribute("semesterPK"));
            });
            li.classList.add("semester-choose");
            document.getElementById("semesters").appendChild(li);
        }
        calcBMS(subjects, notes, (response.length - 1));
    }
}

function calcBMS(subjects, notes, semester) {
    var response = JSON.parse(subjects);
    var subjects = [];
    var endnotes = [];
    response.forEach(subj => {
        if (subj.schoolName == "BMS") {
            subjects[subj.subjectName] = [];
            endnotes[subj.subjectName] = 0;
        }
    });
    var response = JSON.parse(notes);
    response.forEach(note => {
        if (note.schoolName == "BMS") {
            if (note.semesterTag == semester || note.FK_semester == semester) {
                subjects[note.subjectName].push({ value: note.value, examName: note.examName });
            }
        }
    });
    var tr = document.createElement("tr");
    var td = document.createElement("td");
    td.innerHTML = "<div id='splitline'></div>";
    td.colSpan = "2";
    tr.appendChild(td);
    document.getElementById("table-notes-end").appendChild(tr);
    for (var subj in subjects) {
        var tr = document.createElement("tr");
        var th = document.createElement("th");
        th.innerHTML = subj;
        tr.appendChild(th);
        subjects[subj].forEach(note => {
            var td = document.createElement("td");
            td.innerHTML = note.value;
            if (note.value > 4) {
                td.classList.add("grade-good");
            }
            else if (note.value < 4) {
                td.classList.add("grade-bad");
            }
            tr.appendChild(td);
        })
        document.getElementById("table-notes-norm").appendChild(tr);
    };
    var subjectcount = 0;
    var endnoteLast = 0;
    //Make Endnotes
    for (var subj in subjects) {
        endnotes[subj + "_count"] = 0;
        endnotes[subj] = 0;
        subjects[subj].forEach(subje => {
            endnotes[subj] += parseFloat(subje.value);
            if (subje.value != 0) {
                endnotes[subj + "_count"]++;
            }
        })
        if (subj.split(" ")[1] != "Vokabeln") {
            subjectcount++;
        }
    }
    //Add Vokabel endnotes
    for (var subj in subjects) {
        if (subj.split(" ")[1] == "Vokabeln") {
            for (endnote in endnotes) {
                if (endnote == subj.split(" ")[0]) {
                    if (endnotes[subj + "_count"] != 0) {
                        endnotes[subj] = endnotes[subj] / endnotes[subj + "_count"];
                    }
                    endnotes[endnote] += endnotes[subj]
                    endnotes[endnote + "_count"]++;
                }
            }
        }
    }
    //calc Endnotes & display them
    for (var subj in subjects) {
        if (endnotes[subj + "_count"] != 0) {
            endnotes[subj] = endnotes[subj] / endnotes[subj + "_count"];
        }
        endnotes[subj] = Math.round(endnotes[subj] * 2) / 2;
        if (subj.split(" ")[1] != "Vokabeln") {
            if (endnotes[subj] != 0) {
                endnoteLast += endnotes[subj];
            }
            else {
                subjectcount--;
            }
            tr = document.createElement("tr");
            tdh = document.createElement("td");
            tdh.innerHTML = "Endnote " + subj;
            tr.appendChild(tdh);
            tdv = document.createElement("td");
            tdv.innerHTML = endnotes[subj];
            if (endnotes[subj] > 4) {
                tdv.classList.add("grade-good");
            }
            else if (endnotes[subj] < 4 && endnotes[subj] != 0) {
                tdv.classList.add("grade-bad");
            }
            tr.appendChild(tdv);
            document.getElementById("table-notes-end").appendChild(tr);
        }

    }
    var tr = document.createElement("tr");
    var td = document.createElement("td");
    td.innerHTML = "<div id='splitline'></div>";
    td.colSpan = "2";
    tr.appendChild(td);
    document.getElementById("table-notes-end").appendChild(tr);

    if (subjectcount != 0) {
        endnoteLast = endnoteLast / subjectcount;
    }

    var tr = document.createElement("tr");
    var td = document.createElement("td");
    td.innerHTML = "Endnote Gesamt";
    tr.appendChild(td);
    var tde = document.createElement("td");
    tde.innerHTML = endnoteLast;
    if (endnoteLast > 4) {
        tde.classList.add("grade-good");
    }
    else if (endnoteLast < 4 && endnoteLast != 0) {
        tde.classList.add("grade-bad");
    }
    tr.appendChild(tde);
    document.getElementById("table-notes-end").appendChild(tr);
}