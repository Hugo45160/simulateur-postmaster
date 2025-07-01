script.js
// ✅ Script JS complet corrigé pour le simulateur PostMaster™
let formatChoisi = "";
let plateformeChoisie = "";


document.addEventListener("DOMContentLoaded", function () {
  function openConfirmBox(message, onConfirm) {
    const modal = document.getElementById("confirmation-modal");
    const messageElement = modal.querySelector("#confirmation-message");
    const cancelBtn = modal.querySelector("#cancel-confirm-btn");
    const confirmBtn = modal.querySelector("#confirm-confirm-btn");

    messageElement.textContent = message;
    modal.classList.remove("hidden");

    const newConfirm = () => {
      modal.classList.add("hidden");
      confirmBtn.removeEventListener("click", newConfirm);
      cancelBtn.removeEventListener("click", cancel);
      onConfirm();
    };

    const cancel = () => {
      modal.classList.add("hidden");
      confirmBtn.removeEventListener("click", newConfirm);
      cancelBtn.removeEventListener("click", cancel);
    };

    confirmBtn.addEventListener("click", newConfirm);
    cancelBtn.addEventListener("click", cancel);
  }

  const steps = document.querySelectorAll(".step-content");
  const timelineSteps = document.querySelectorAll(".timeline-step");
  let scrollY = 0;
  let currentStep = 1;

  const retourBtn = document.getElementById("btnRetourHaut");
  const closeBtn = document.getElementById("closeSimulator");
  const loadingOverlay = document.getElementById("loading-overlay");
  const loadingText = document.getElementById("loaderText");
  const loadingBar = document.getElementById("loaderProgress");
  const dynamicHeading = document.getElementById("dynamicStepHeading");

  const subtextsOriginal = {
    1: "Choisis où tu publies",
    2: "Visuel, vidéo ou carrousel",
    3: "Entre ton message",
    4: "Ajoute ton image",
    5: "Analyse IA"
  };

  const stepTitles = {
    1: {
      title: "Étape 1 – Choisis ta plateforme",
      desc: "Chaque réseau a ses propres codes. Précise où tu veux publier ton contenu."
    },
    2: {
      title: "Étape 2 – Choisis ton format",
      desc: "Visuel, vidéo, story ou carrousel : chaque format sera analysé différemment."
    },
    3: {
      title: "Étape 3 – Écris ton texte",
      desc: "C’est ici que tu rédiges le texte de ta publication. Celui qui va accrocher ton audience dès les premiers mots."
    },
    4: {
      title: "Étape 4 – Ajoute ton visuel",
      desc: "Ton image, ton identité. Elle sera aussi prise en compte dans l’analyse finale."
    },
    5: {
      title: "Étape 5 – Résultat",
      desc: "Découvre ton score PostMaster™ et nos recommandations IA personnalisées."
    }
  };

  const loadingPhrases = {
    1: ["🚀 Lancement de PostMaster™ en cours..."],
    2: ["📍 Enregistrement de ta plateforme...", "🔎 Adaptation de PostMaster™ au réseau..."],
    3: ["🎯 Préparation de l'analyse du format..."],
    4: ["📄 Lecture du message en cours..."]
  };

  function updateSubtext(stepIndex, value) {
    const el = document.querySelector(`.timeline-step[data-step="${stepIndex}"] .timeline-subtext`);
    if (el) {
      el.textContent = value;
      el.classList.add("dynamic-selected");
    }
  }

  function resetSubtext(stepIndex) {
    const el = document.querySelector(`.timeline-step[data-step="${stepIndex}"] .timeline-subtext`);
    if (el) {
      el.textContent = subtextsOriginal[stepIndex];
      el.classList.remove("dynamic-selected");
    }
  }

  function resetSimulator() {
    document.querySelectorAll(".platform-card").forEach(c => c.classList.remove("selected"));
    formatChoisi = "";
    plateformeChoisie = "";
    document.getElementById("step-3-form").innerHTML = "";
    Object.keys(subtextsOriginal).forEach(i => resetSubtext(i));
  }

  function showStep(index) {
    currentStep = index;
    steps.forEach(s => s.classList.remove("active"));
    document.getElementById("step-" + index)?.classList.add("active");

    if (dynamicHeading && stepTitles[index]) {
      dynamicHeading.querySelector("h2").textContent = stepTitles[index].title;
      dynamicHeading.querySelector(".step-description").textContent = stepTitles[index].desc;
    }

    retourBtn?.classList.toggle("hidden", index <= 1);

    timelineSteps.forEach((step, i) => {
      const circle = step.querySelector(".timeline-circle");
      const bar = step.querySelector(".timeline-bar");
      step.classList.remove("active", "completed");
      circle?.classList.remove("active");
      if (i < index - 1) step.classList.add("completed");
      if (i === index - 1) step.classList.add("active");
      if (bar) bar.style.setProperty('--progress', i < index ? '100%' : '0%');
    });

    if (index === 4) updateStep4Content(formatChoisi);
    if (index === 3) updateStep3Content(formatChoisi);
    if (index < 3) document.getElementById("step-3-form").innerHTML = "";
  }

  function updateCharCountListeners() {
    const container = document.getElementById("step-3-form");
    const allTextareas = container.querySelectorAll(".slide-textarea");
    allTextareas.forEach(textarea => {
      const counter = container.querySelector(`.char-count[data-index="${textarea.dataset.index}"]`);
      textarea.addEventListener("input", () => {
        counter.textContent = `${textarea.value.length}/400`;
      });
    });
  }

  function createNextButton() {
    const navDiv = document.createElement("div");
    navDiv.className = "step-nav";

    const nextBtn = document.createElement("button");
    nextBtn.className = "next-btn";
    nextBtn.dataset.next = "4";
    nextBtn.innerHTML = "Suivant →";

    navDiv.appendChild(nextBtn);
    return navDiv;
  }

 function updateStep3Content(format) {
  const container = document.getElementById("step-3-form");
  container.innerHTML = "";

  if (format.toLowerCase().includes("carrousel")) {
    let count = 2;
    const maxSlides = 10;

    const slidesWrapper = document.createElement("div");
    slidesWrapper.classList.add("slides-wrapper");

    function createSlide(index) {
      const slide = document.createElement("div");
      slide.className = "slide-block carrousel-slide";

      const label = document.createElement("label");
      label.className = "slide-label";
      label.innerHTML = `Slide ${index} <span class="char-count" data-index="${index}" style="float: right;">0/1000</span>`;

      const textarea = document.createElement("textarea");
      textarea.className = "slide-textarea";
      textarea.rows = 4;
      textarea.maxLength = 1000;
      textarea.setAttribute("data-index", index);

      textarea.addEventListener("input", () => {
        const charCount = textarea.value.length;
        const counter = label.querySelector(".char-count");
        if (counter) counter.textContent = `${charCount}/1000`;
      });

      slide.appendChild(label);
      slide.appendChild(textarea);
      return slide;
    }

    for (let i = 1; i <= count; i++) {
      slidesWrapper.appendChild(createSlide(i));
    }

    const topLabel = document.createElement("label");
    topLabel.className = "text-label";
    topLabel.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <strong style="font-weight: 600;">Rédige les textes de ton carrousel</strong>
          <div class="tooltip-container">
            <span class="info-icon">i</span>
            <div class="tooltip-text">
              Chaque slide est une étape clé de ton message.<br>
              Tu peux en ajouter jusqu’à 10 pour construire un carrousel percutant.<br><br>
              <strong>Conseil :</strong> commence fort, varie les rythmes, et termine avec un appel à l’action.
            </div>
          </div>
        </div>
        <span id="carrousel-counter" style="color: #FFA500; font-weight: 600;">${count} / ${maxSlides} slides utilisées</span>
      </div>
    `;

    container.appendChild(topLabel);

    const addBtn = document.createElement("button");
    addBtn.className = "add-slide-btn";
    addBtn.innerHTML = `
      <div class="btn-flex">
        <span class="btn-icon">+</span>
        <span class="btn-text">Ajouter une slide</span>
      </div>
    `;

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-slide-btn";
    removeBtn.innerHTML = `
      <div class="btn-flex">
        <span class="btn-icon">−</span>
        <span class="btn-text">Supprimer une slide</span>
      </div>
    `;
    removeBtn.style.display = "none";

    addBtn.onclick = () => {
      if (count >= maxSlides) return;
      count++;
      slidesWrapper.appendChild(createSlide(count));
      updateCarrouselCounter(count);
      slidesWrapper.scrollIntoView({ behavior: "smooth", block: "end" });
      if (count > 2) removeBtn.style.display = "inline-block";
    };

    removeBtn.onclick = () => {
      if (count > 2) {
        slidesWrapper.removeChild(slidesWrapper.lastChild);
        count--;
        updateCarrouselCounter(count);
        if (count <= 2) removeBtn.style.display = "none";
      }
    };

    function updateCarrouselCounter(count) {
      document.getElementById("carrousel-counter").textContent = `${count} / ${maxSlides} slides utilisées`;
    }

    const btnWrapper = document.createElement("div");
    btnWrapper.className = "slide-btn-wrapper";
    btnWrapper.appendChild(addBtn);
    btnWrapper.appendChild(removeBtn);

const formWrapper = document.createElement("div");
formWrapper.className = "form-slide-wrapper";
formWrapper.appendChild(slidesWrapper);
formWrapper.appendChild(btnWrapper);

//⬇️ Il manquait cette ligne !
container.appendChild(formWrapper);

// ⬇️ Ensuite le bouton suivant
container.appendChild(createNextButton());


  } else {
    container.innerHTML = `
      <label for="postText" class="text-label" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <strong style="font-weight: 600;">Rédige le texte principal de ton post</strong>
          <div class="tooltip-container">
            <span class="info-icon">i</span>
            <div class="tooltip-text">
              Ce texte correspond au cœur de ta publication.<br>
              Il peut être utilisé dans un carrousel, une vidéo ou un visuel.<br><br>
              <strong>L’objectif :</strong> capter l’attention dès les premières secondes.
            </div>
          </div>
        </div>
        <span id="text-counter" style="color: #FFA500;">0/1000</span>
      </label>
      <textarea id="postText" name="postText" maxlength="1000" rows="6" placeholder="Écris ici ton post..."></textarea>
      <div class="text-guidelines">✍️ Un bon post commence toujours par un message simple, direct et sincère.</div>
    `;

    const navDiv = document.createElement("div");
    navDiv.className = "step-nav";
    const nextBtn = document.createElement("button");
    nextBtn.className = "next-btn";
    nextBtn.dataset.next = "4";
    nextBtn.innerHTML = "Suivant →";
    navDiv.appendChild(nextBtn);
    container.appendChild(navDiv);

    const textarea = container.querySelector("#postText");
    const counter = container.querySelector("#text-counter");

    textarea.addEventListener("input", () => {
      counter.textContent = `${textarea.value.length}/1000`;
    });
  }
}


function updateStep4Content(format) {
  const container = document.getElementById("step-4-form");
  container.innerHTML = "";

  if (!format || typeof format !== "string") {
    console.warn("Format non défini à l'étape 4");
    return;
  }

  const cleanedFormat = format.toLowerCase().replace(/\s+/g, "");
  const step4 = document.getElementById("step-4");
  step4.classList.remove("format-carrousel", "format-visuel-simple", "format-video", "format-texte");

// === 🟠 CAS 1 — Carrousel
if (cleanedFormat.includes("carrousel")) {
  step4.classList.add("format-carrousel");

  let count = 2;
  const maxSlides = 10;

  const slidesWrapper = document.createElement("div");
  slidesWrapper.classList.add("slides-wrapper");
  slidesWrapper.setAttribute("id", "carrouselSlides");

  function createSlide(index) {
    const slide = document.createElement("div");
    slide.className = "slide-block carrousel-slide";
    slide.innerHTML = `
      <label class="slide-label">Image ${index}</label>
      <div class="slide-input-wrapper">
        <input type="file" accept="image/*" class="image-input" onchange="previewImage(event, ${index})">
        <div id="preview-${index}" class="image-preview-wrapper"></div>
      </div>
    `;
    return slide;
  }

  for (let i = 1; i <= count; i++) {
    slidesWrapper.appendChild(createSlide(i));
  }

  const topLabel = document.createElement("label");
  topLabel.className = "text-label";
  topLabel.innerHTML = `
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <strong style="font-weight: 600;">Ajoute les images finales de ton carrousel</strong>
        <div class="tooltip-container">
          <span class="info-icon">i</span>
          <div class="tooltip-text">
            Glisse ici les visuels **finaux** prêts à être publiés : chaque image doit déjà contenir le texte intégré si besoin.<br><br>
            <strong>Conseil :</strong> pense à la cohérence visuelle entre les slides (couleurs, typographie, ton).
          </div>
        </div>
      </div>
      <span id="image-counter" style="color: #FFA500; font-weight: 600;">${count} / ${maxSlides} images utilisées</span>
    </div>
  `;

  const addBtn = document.createElement("button");
  addBtn.className = "add-slide-btn";
  addBtn.innerHTML = `<div class="btn-flex"><span class="btn-icon">+</span><span class="btn-text">Ajouter une image</span></div>`;

  const removeBtn = document.createElement("button");
  removeBtn.className = "remove-slide-btn";
  removeBtn.innerHTML = `<div class="btn-flex"><span class="btn-icon">−</span><span class="btn-text">Supprimer une image</span></div>`;
  removeBtn.style.display = "none";

  addBtn.onclick = () => {
    if (count >= maxSlides) return;
    count++;
    slidesWrapper.appendChild(createSlide(count));
    document.getElementById("image-counter").innerHTML = `${count} / ${maxSlides} images utilisées`;
    if (count > 2) removeBtn.style.display = "inline-block";
  };

  removeBtn.onclick = () => {
    if (count > 2) {
      slidesWrapper.removeChild(slidesWrapper.lastChild);
      count--;
      document.getElementById("image-counter").innerHTML = `${count} / ${maxSlides} images utilisées`;
      if (count <= 2) removeBtn.style.display = "none";
    }
  };

  const btnWrapper = document.createElement("div");
  btnWrapper.className = "slide-btn-wrapper";
  btnWrapper.appendChild(addBtn);
  btnWrapper.appendChild(removeBtn);

  const formWrapper = document.createElement("div");
  formWrapper.className = "form-slide-wrapper";
  formWrapper.appendChild(topLabel);
  formWrapper.appendChild(slidesWrapper);
  formWrapper.appendChild(btnWrapper);
  formWrapper.appendChild(createFinalButton());

  container.appendChild(formWrapper);
}

// === 🟢 CAS 2 — Visuel simple
else if (cleanedFormat.includes("visuel")) {
  step4.classList.add("format-visuel-simple");

  const formWrapper = document.createElement("div");
  formWrapper.className = "form-slide-wrapper";

  const label = document.createElement("label");
  label.className = "text-label";
  label.innerHTML = `
    <strong>Ajoute le visuel final de ton post</strong>
    <div class="tooltip-container">
      <span class="info-icon">i</span>
      <div class="tooltip-text">
        Ajoute ici le visuel **terminé**, avec le texte intégré si besoin (pas une simple image de fond).<br><br>
        <strong>Conseil :</strong> un contraste fort et un message lisible dès la première seconde augmentent l’impact.
      </div>
    </div>
  `;

  const fileRow = document.createElement("div");
  fileRow.className = "slide-block carrousel-slide";
  fileRow.innerHTML = `
    <label class="slide-label">Image</label>
    <div class="slide-input-wrapper">
      <input type="file" accept="image/*" class="image-input" onchange="previewSingleImage(event)">
      <div id="singlePreview" class="image-preview-wrapper"></div>
    </div>
  `;

  const guidelines = document.createElement("div");
  guidelines.className = "text-guidelines";
  guidelines.innerText = "📷 Ce visuel sera analysé tel quel : assure-toi qu’il représente bien ton post final.";

  formWrapper.appendChild(label);
  formWrapper.appendChild(fileRow);
  formWrapper.appendChild(guidelines);
  formWrapper.appendChild(createFinalButton());

  container.appendChild(formWrapper);
}

// === 🔵 CAS 3 — Vidéo, Reel ou Story
else if (cleanedFormat.includes("video") || cleanedFormat.includes("reel") || cleanedFormat.includes("story")) {
  step4.classList.add("format-video");

  const formWrapper = document.createElement("div");
  formWrapper.className = "form-slide-wrapper";

  const innerScrollWrapper = document.createElement("div");
  innerScrollWrapper.className = "slides-wrapper";

  const label = document.createElement("label");
  label.className = "text-label";
  label.innerHTML = `
    <strong>Ajoute une image de couverture ou un aperçu</strong>
    <div class="tooltip-container">
      <span class="info-icon">i</span>
      <div class="tooltip-text">
        Ce visuel sert à représenter le **style visuel** de ta vidéo/story/reel : image de couverture, miniature ou capture d’écran avec textes et effets visibles.<br><br>
        <strong>Conseil :</strong> choisis un moment fort, bien éclairé, qui reflète l’ambiance de ta vidéo.
      </div>
    </div>
  `;

  const fileRow = document.createElement("div");
  fileRow.className = "slide-block carrousel-slide";
  fileRow.innerHTML = `
    <label class="slide-label">Image d’illustration</label>
    <div class="slide-input-wrapper">
      <input type="file" accept="image/*" class="image-input" onchange="previewSingleImage(event)">
      <div id="singlePreview" class="image-preview-wrapper"></div>
    </div>
  `;

  const textLabel = document.createElement("label");
  textLabel.className = "slide-block carrousel-slide";
  textLabel.style.marginTop = "16px";
  textLabel.innerHTML = `
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <strong style="font-weight: 600;">Décris ta vidéo</strong>
        <div class="tooltip-container">
          <span class="info-icon">i</span>
          <div class="tooltip-text">
            Tu peux préciser ici l’intention de ta vidéo, son format, ou son objectif.<br><br>
            <strong>Astuce :</strong> ce champ reste optionnel, mais il aide à améliorer l’analyse.
          </div>
        </div>
      </div>
      <span id="desc-counter" class="char-counter">0/400</span>
    </div>
    <textarea id="desc-textarea" class="image-description" maxlength="400" placeholder="Décris brièvement ta vidéo (max 400 caractères)" style="margin-top: 12px;"></textarea>
  `;

  const descTextarea = textLabel.querySelector("#desc-textarea");
  const counter = textLabel.querySelector("#desc-counter");

  descTextarea.addEventListener("input", () => {
    counter.textContent = `${descTextarea.value.length}/400`;
  });

  const guidelines = document.createElement("div");
  guidelines.className = "text-guidelines";
  guidelines.innerText = "🎥 Ton image + ta description permettront une analyse plus pertinente.";

  innerScrollWrapper.appendChild(fileRow);
  innerScrollWrapper.appendChild(textLabel);

  formWrapper.appendChild(label);
  formWrapper.appendChild(innerScrollWrapper);
  formWrapper.appendChild(guidelines);
  formWrapper.appendChild(createFinalButton());

  container.appendChild(formWrapper);
}

// === 🟣 CAS 4 — Texte simple
else {
  step4.classList.add("format-texte");

  const formWrapper = document.createElement("div");
  formWrapper.className = "form-slide-wrapper";

  const label = document.createElement("label");
  label.className = "text-label";
  label.innerHTML = `
    <strong>Tu peux joindre un visuel si tu veux</strong>
    <div class="tooltip-container">
      <span class="info-icon">i</span>
      <div class="tooltip-text">
        Même un texte seul peut être analysé, mais un visuel final (avec texte intégré) peut enrichir l’analyse visuelle.<br><br>
        <strong>Conseil :</strong> inutile d’ajouter une image si ton post n’en contient pas dans sa version finale.
      </div>
    </div>
  `;

  const fileRow = document.createElement("div");
  fileRow.className = "slide-block carrousel-slide";
  fileRow.innerHTML = `
    <label class="slide-label">Image (facultatif)</label>
    <div class="slide-input-wrapper">
      <input type="file" accept="image/*" class="image-input" onchange="previewSingleImage(event)">
      <div id="singlePreview" class="image-preview-wrapper"></div>
    </div>
  `;

  const guidelines = document.createElement("div");
  guidelines.className = "text-guidelines";
  guidelines.innerText = "📌 Une image n’est pas obligatoire ici, mais peut renforcer l’analyse.";

  formWrapper.appendChild(label);
  formWrapper.appendChild(fileRow);
  formWrapper.appendChild(guidelines);
  formWrapper.appendChild(createFinalButton());

  container.appendChild(formWrapper);
}
}





function createFinalButton() {
  const navDiv = document.createElement("div");
  navDiv.className = "step-nav";
  const nextBtn = document.createElement("button");
  nextBtn.className = "next-btn";
  nextBtn.dataset.next = "5";
  nextBtn.innerHTML = `<div class="btn-flex"><span class="btn-text">Analyser</span><span class="btn-icon">→</span></div>`;
  navDiv.appendChild(nextBtn);
  return navDiv;
}






  function simulateLoadingAndShowStep(index) {
    if (index === 5) return showStep(index);
    loadingText.textContent = loadingPhrases[index][0];
    loadingOverlay.classList.remove("hidden");
    loadingBar.style.width = "0%";
    setTimeout(() => loadingBar.style.width = "100%", 50);
    setTimeout(() => {
      loadingOverlay.classList.add("hidden");
      showStep(index);
      setTimeout(() => loadingBar.style.width = "0%", 300);
    }, 2500);
  }





  retourBtn?.addEventListener("click", () => {
    if (currentStep > 1) {
      if (currentStep >= 3) {
        openConfirmBox("Revenir en arrière supprimera les données de cette étape. Continuer ?", () => {
          if (currentStep === 3) {
            formatChoisi = "";
            resetSubtext(2);
          }
          if (currentStep === 2) {
            plateformeChoisie = "";
            resetSubtext(1);
          }
          showStep(currentStep - 1);
        });
      } else {
        showStep(currentStep - 1);
      }
    }
  });

  document.querySelectorAll("#step-1 .platform-card").forEach(card => {
    card.addEventListener("click", () => {
      document.querySelectorAll("#step-1 .platform-card").forEach(c => c.classList.remove("selected"));
      card.classList.add("selected");
      plateformeChoisie = card.querySelector("span")?.textContent.trim();
      updateSubtext(1, plateformeChoisie);
      simulateLoadingAndShowStep(2);
    });
  });

  document.querySelectorAll("#step-2 .platform-card").forEach(card => {
    card.addEventListener("click", () => {
      document.querySelectorAll("#step-2 .platform-card").forEach(c => c.classList.remove("selected"));
      card.classList.add("selected");
      formatChoisi = card.querySelector("span")?.textContent.trim();
      updateSubtext(2, formatChoisi);
      simulateLoadingAndShowStep(3);
    });
  });

  document.addEventListener("click", (e) => {
  if (e.target.classList.contains("next-btn")) {
    const next = parseInt(e.target.dataset.next);
    if (next === 5) return; // ⛔ Empêche d’aller à l’étape 5 tout de suite
    simulateLoadingAndShowStep(next);
  }
});

});
// Toute la fonction updateStep4Content ici...

function previewSingleImage(event) {
  const input = event.target;
  const preview = document.getElementById("singlePreview");

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.innerHTML = `<img src="${e.target.result}" class="image-preview" alt="Aperçu image">`;
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    preview.innerHTML = "";
  }
}

document.querySelectorAll('.tooltip-container').forEach(container => {
  const icon = container.querySelector('.info-icon');
  const tooltip = container.querySelector('.tooltip-text');

  let isVisible = false;

  icon.addEventListener('click', (e) => {
    e.stopPropagation(); // empêche de fermer immédiatement
    isVisible = !isVisible;
    tooltip.style.visibility = isVisible ? 'visible' : 'hidden';
    tooltip.style.opacity = isVisible ? '1' : '0';
  });

  document.addEventListener('click', () => {
    if (isVisible) {
      tooltip.style.visibility = 'hidden';
      tooltip.style.opacity = '0';
      isVisible = false;
    }
  });
});

async function compresserImage(file, quality = 0.7, maxWidth = 1080) {
  return new Promise((resolve, reject) => {
    const image = new Image();
    image.src = URL.createObjectURL(file);

    image.onload = () => {
      const scale = Math.min(1, maxWidth / image.width);
      const canvas = document.createElement("canvas");
      canvas.width = image.width * scale;
      canvas.height = image.height * scale;

      const ctx = canvas.getContext("2d");
      ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

      canvas.toBlob(
        (blob) => {
          const compressedFile = new File([blob], file.name, { type: "image/jpeg" });
          resolve(compressedFile);
        },
        "image/jpeg",
        quality
      );
    };

    image.onerror = reject;
  });
}

// ✅ GESTION ÉTAPE 4 → 5
document.addEventListener("click", function (e) {
  const btn = e.target.closest(".next-btn[data-next='5']");
  if (!btn) return;
  e.preventDefault();

  console.log("➡️ Étape 4 → 5 déclenchée");

  if (formatChoisi.toLowerCase().includes("carrousel")) {
    const slides = document.querySelectorAll("#step-3 .slide-textarea");
    const allFilled = Array.from(slides).every(t => t.value.trim().length >= 50);
    console.log("🧾 Validation carrousel :", allFilled);
    if (!allFilled) return alert("❌ Chaque slide du carrousel doit contenir au moins 50 caractères.");
  } else {
    const text = document.querySelector("#postText");
    console.log("🧾 Validation texte simple :", text?.value.trim().length);
    if (!text || text.value.trim().length < 50) return alert("❌ Le texte doit contenir au moins 50 caractères.");
  }

  const imageInputs = document.querySelectorAll("#step-4 input[type='file']");
  const hasImage = Array.from(imageInputs).some(input => input.files.length > 0);
  console.log("🖼️ Validation image présente :", hasImage);

  if (
    ["visuel", "carrousel", "video", "reel", "story"].some(f => formatChoisi.toLowerCase().includes(f)) &&
    !hasImage
  ) return alert("❌ Ajoute au moins une image pour continuer.");

  envoyerPostmasterAPI();
});

// ✅ ENVOI À L’API POSTMASTER™
async function envoyerPostmasterAPI() {
  console.log("📤 Envoi à PostMaster™ démarré");
  const formData = new FormData();
  formData.append("plateforme", plateformeChoisie || "");
  formData.append("format", formatChoisi || "");

  if (formatChoisi.toLowerCase().includes("carrousel")) {
    document.querySelectorAll("#step-3 .slide-textarea").forEach((t, i) => {
      formData.append(`texte_carrousel_${i + 1}`, t.value.trim());
    });
  } else {
    const textePrincipal = document.querySelector("#postText");
    if (textePrincipal) formData.append("texte_principal", textePrincipal.value.trim());
  }

  if (formatChoisi.toLowerCase().includes("carrousel")) {
    document.querySelectorAll("#step-4 input[type='file']").forEach((input, i) => {
      if (input.files[0]) formData.append(`image_carrousel_${i + 1}`, input.files[0]);
    });
  } else {
    const imageInput = document.querySelector("#step-4 input[type='file']");
    if (imageInput?.files[0]) {
      formData.append("image_simple", imageInput.files[0]);
    }
  }

  const descTextarea = document.querySelector("#desc-textarea");
  if (descTextarea) formData.append("description_video", descTextarea.value.trim());

  for (let pair of formData.entries()) {
    console.log(`📦 ${pair[0]} =>`, pair[1]);
  }

  document.querySelector(".resultat-ia").innerHTML = "⏳ Analyse en cours...";
  try {
    const res = await fetch("/wp-content/themes/Avada-Child-Theme/postmaster.php", {
      method: "POST",
      body: formData,
    });
    console.log("📨 Requête envoyée à postmaster.php");

    const texte = await res.text();
    console.log("📩 Réponse brute reçue :", texte);

    if (!texte || texte.trim().length < 10) {
      console.error("❌ Réponse vide ou invalide.");
      document.querySelector(".resultat-ia").innerHTML = "❌ Réponse vide ou invalide.";
      return;
    }

    document.querySelectorAll(".step-content").forEach(el => el.classList.remove("active"));
    document.getElementById("step-5").classList.add("active");

    afficherTextePostmaster(texte);

  } catch (error) {
    console.error("❌ Erreur réseau :", error);
    document.querySelector(".resultat-ia").innerHTML = "❌ Problème de connexion à l’API.";
  }
}

// ✅ AFFICHAGE STRUCTURÉ ÉTAPE 5
function afficherTextePostmaster(texte) {
  const container = document.querySelector(".resultat-ia");
  container.innerHTML = "";
  container.classList.add("analyse-resultat-scroll");

  const lignes = texte.trim().split(/\n+/);
  console.log("🧾 Contenu structuré analysé :", lignes);

  let scoreTexteNon = null;
  let scoreVisuelNon = null;

  lignes.forEach(ligne => {
    ligne = ligne.trim();
    const matchTexte = ligne.match(/Score texte\s*:\s*(\d+)/i);
    const matchVisuel = ligne.match(/Score visuel\s*:\s*(\d+)/i);
    if (matchTexte) scoreTexteNon = parseInt(matchTexte[1]);
    if (matchVisuel) scoreVisuelNon = parseInt(matchVisuel[1]);
  });

  const scoreTexte = typeof scoreTexteNon === "number" ? 50 - scoreTexteNon : null;
  const scoreVisuel = typeof scoreVisuelNon === "number" ? 50 - scoreVisuelNon : null;
  const scoreGlobal = (scoreTexte ?? 0) + (scoreVisuel ?? 0);

  console.log("📊 Score texte :", scoreTexte);
  console.log("📊 Score visuel :", scoreVisuel);
  console.log("📊 Score global :", scoreGlobal);

  const makeCercle = (score, label, classe) => {
    if (score == null || isNaN(score)) score = 0;
    const color =
      score >= 85 ? "#00C853" :
      score >= 70 ? "#FFA500" :
      score >= 50 ? "#FF5722" : "#E53935";
    const dash = Math.min(score * 2.83, 283);

    const div = document.createElement("div");
    div.className = classe;
    div.innerHTML = `
      <svg viewBox="0 0 100 100" class="score-cercle-svg">
        <circle cx="50" cy="50" r="45" class="cercle-bg" />
        <circle cx="50" cy="50" r="45" class="cercle-val"
          style="stroke:${color}; stroke-dasharray:${dash} 283;" />
        <text x="50" y="54" class="cercle-texte">${score}</text>
      </svg>
      <div class="cercle-label">${label}</div>
    `;
    return div;
  };

  const blocNotes = document.createElement("div");
  blocNotes.className = "score-trio";
 blocNotes.appendChild(makeCercle(scoreTexte * 2, "Texte", "cercle-secondaire"));
blocNotes.appendChild(makeCercle(scoreGlobal, "Global", "cercle-global"));
blocNotes.appendChild(makeCercle(scoreVisuel * 2, "Visuel", "cercle-secondaire"));
  container.appendChild(blocNotes);

  lignes.forEach(ligne => {
    const div = document.createElement("div");
    div.classList.add("texte-ligne");
    div.textContent = ligne;
    container.appendChild(div);
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const closeBtn = document.getElementById("closeSimulator");
  if (closeBtn) {
    closeBtn.addEventListener("click", function () {
      // Redirige vers la page précédente (si elle existe)
      if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
      } else {
        // Sinon, redirige vers l'accueil
        window.location.href = " http://codigi.local";
      }
    });
  }
});
