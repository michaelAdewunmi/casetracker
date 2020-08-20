if (undefined == $) {
    var $ = jQuery;
}

class RegistrationScript {

    constructor() {
        const tags = localStorage.getItem("allTagsToSave");
        if (tags && this.isJSON(tags) && (JSON.parse(tags)).length >0) {
            alert("NOTE: The Previously assigned tags has been lost. Please Ensure you re-enter the tags before submitting.");
        }
        localStorage.removeItem("allTagsToSave");
        localStorage.removeItem("allTags");
    }

    eventListeners() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        $(".casetracker-input").each(this.moveLabelUpIfNotEmpty.bind(this));
        $("label").on("click", this.focusInputSibling.bind(this));
        $(".casetracker-input").on("focus", this.moveLabelUp.bind(this));
        $(".casetracker-input").on("blur", this.returnLabelIfEmpty.bind(this));
        $("select").on("focus", this.removeErroredClass.bind(this));
        $("#tag-input").keyup( (e) => {
            if(e.key=="Enter" && e.keyCode=="13") {
                const newTag = $(e.target).val();
                this.storePickedTagTemp(newTag);
                $(e.target).val('');
            }
        });
        $("#case-tags").val(JSON.stringify([]));
        this.outputAllTags();
    }

    isJSON(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }


    storePickedTagTemp(newVal, fromRadioBtn=false, radioBtn=null) {
        console.log(newVal);
        let allTags = localStorage.getItem("allTagsToSave");
        let val;
        if(allTags==null || allTags=='' || allTags==undefined) {
            val = [];
        } else {
            val = JSON.parse(allTags);
        }

        //Get all present available Tags
        const h = $(".cb-tags")
        const presetTags = [];
        [...h].forEach(j=>{
            presetTags.push(j.id);
        });
        const transformToLower = newVal.toLowerCase().replace(/ /g, "_")
        if(newVal!='' && Array.isArray(val) && val.indexOf(newVal)==-1) {
            if( !fromRadioBtn && presetTags.indexOf(transformToLower)>-1 ) {
                alert("This tag already exists! Please Use the Radio Button to add existing tags")
            } else {
                val.push(newVal);
            }
        }
        let tagsToAdd = JSON.stringify(val);
        localStorage.setItem("allTagsToSave", tagsToAdd);
        this.outputAllTags();
        //return val.indexOf(newVal);
    }


    outputAllTags() {
        let allTags = localStorage.getItem("allTagsToSave");
        let val;
        val = allTags!=null && allTags!='' && allTags!=undefined
            ? JSON.parse(allTags) : [];
        $("#tags-list").html('');
        if(Array.isArray(val)) {
            val.forEach(tag=>{
                let index = val.indexOf(tag);
                let separator = index>0 ? '<span class="separator" style="font-weight: bold;">|</span>' : '';
                if(typeof(tag) == "string") {
                    $("#tags-list").append(
                        `${separator}<l1 class="tagList" id="item_${tag.toLowerCase().replace(/ /g, "_")}">
                            ${tag}&nbsp&nbsp&nbsp<i class="fa fa-times del-comm del_btn" data-index=${index}
                            aria-hidden="true" onClick="regscript.deleteTag(this)"></i>
                        </l1>`
                    );
                } else {
                    const h = $(".cb-tags");
                    [...h].forEach(j=>{
                        const termid = j.getAttribute("data-termid");
                        const tagName = j.getAttribute("data-name")
                        if(tag===Number(termid)) {
                            $("#tags-list").append(
                                `${separator}<l1 class="tagList" id="item_${tagName.toLowerCase().replace(/ /g, "_")}">
                                    ${tagName}&nbsp<i class="fa fa-times del-comm del_btn" data-index=${index}
                                    aria-hidden="true" onClick="regscript.deleteTag(this)"></i>
                                </l1>`
                            );
                        }
                    });
                }

            })
        }
        this.setTagsInputValues();
    }

    setTagsInputValues() {
        $("#case-tags").val(localStorage.getItem("allTagsToSave"));
    }

    deleteTag(element, index=null) {
        let tagIndex;
        if(index===null && element!==null) {
            tagIndex = element.getAttribute("data-index")
        } else {
            tagIndex = index;
        }
        let allTags = localStorage.getItem("allTagsToSave");
        let val = allTags!=null && allTags!='' && allTags!=undefined
            ? JSON.parse(allTags) : [];
        val.splice(tagIndex, 1);
        let newTagsToAdd = JSON.stringify(val);
        localStorage.setItem("allTagsToSave", newTagsToAdd);
        //Get all present available Tags
        const h = $(".cb-tags");
        [...h].forEach(j=>{
            if (j.getAttribute("id")===element.parentNode.getAttribute("id").slice(5)) {
                $(j).prop("checked", false);
            }
        });
        this.outputAllTags();
    }

    addTagFromRadio(elem) {
        const $ = jQuery
        console.log(elem);
        if($(elem).prop("checked")===true) {
            this.storePickedTagTemp(elem.getAttribute("data-name"), true, elem);
        } else {
            [...$(".tagList")].forEach(tagDiv => {
                const radioId = elem.getAttribute("id");
                const dislayedListItemTagId = tagDiv.getAttribute("id")
                if(radioId===dislayedListItemTagId.slice(5)) {
                    this.deleteTag(tagDiv.firstElementChild)
                }
            })
        }
    }

    removeErroredClass(e) {
        this.typingUtilitiesHandler(e);
        $(e.currentTarget).removeClass("errored");
    }

    typingUtilitiesHandler(e) {
        $(e.currentTarget.parentNode).find(".error-string").css({"transform": "scale(0)"})
    }

    showErrorIfNecessary(e) {
        if (e.currentTarget.value.trim()==="") {
            $(e.currentTarget).addClass("errored");
            $(e.currentTarget.parentNode).find(".error-string").css({"transform": "scale(1)"})
        }
    }

    moveLabelUpIfNotEmpty(e, item) {
        var inputVal = $(item).context.value;
        var inputLabel = $($(item).context.previousElementSibling);

        if (inputVal.trim()!="") {
            this.adjustLabelCss(inputLabel);
        }
    }

    adjustLabelCss(elem) {
        elem.css({
            "top": "-30px",
            "left": 0,
            "font-size": "10px"
        })
    }

    focusInputSibling(e) {
        console.log(e);
        const elementSibling = $(e.target.nextElementSibling);
        if (elementSibling.context && elementSibling.context.nodeName==="INPUT") {
            //Foucsing the Input should trigger the moveLabelUp Function
            elementSibling.focus();
        }
    }

    moveLabelUp(e) {
        this.removeErroredClass(e);
        const input = e.currentTarget;
        const inputLabel = $(input.previousElementSibling)

        this.adjustLabelCss(inputLabel);
    }

    returnLabelIfEmpty(e) {
        this.showErrorIfNecessary(e);
        const input = e.target;
        const inputVal = $(input).val();

        if (inputVal=="" || inputVal.trim()=="") {
            $(input.previousElementSibling).css({
                "top": "18px",
                "left": "15px",
                "font-size": "1.6rem"
            })
        }
    }
}

const regscript = new RegistrationScript;

$(window).load(regscript.eventListeners.bind(regscript));