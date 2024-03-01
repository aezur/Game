import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { MarketGladiator, PageProps } from "@/types";
import PrimaryButton from "@/Components/PrimaryButton";
import { Fragment, useMemo, useState } from "react";
import { useAxios } from "@/Hooks/useAxios";
import { useErrorHandler } from "@/Hooks/useErrorHandler";
import Card from "@/Components/Card";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import { useMidnightCountdown } from "@/Hooks/useMidnightCountdown";

export default function Market({
  auth,
  data,
}: PageProps<{ data: MarketGladiator[] }>) {
  const [gladiators, setGladiators] = useState<MarketGladiator[]>(data);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { axios } = useAxios();
  const { handleError } = useErrorHandler();
  const [showModal, setShowModal] = useState(false);
  const [selectedGladiator, setSelectedGladiator] = useState<MarketGladiator>();
  const { formattedCounter } = useMidnightCountdown();

  const leftToPurchase = useMemo(() => {
    return gladiators.filter((g) => !g.purchased).length;
  }, [gladiators]);

  const openModal = (gladiator: MarketGladiator) => {
    setSelectedGladiator(gladiator);
    setShowModal(true);
  };

  const closeModal = () => {
    setShowModal(false);

    // Wait for the modal to close before resetting the selected gladiator
    setSelectedGladiator(undefined);
  };

  const purchase = async (id: number) => {
    try {
      setIsSubmitting(true);
      await axios.post(route("market.purchase", { id }));
      setGladiators((old) =>
        old.map((g) => {
          if (g.id === id) {
            g.purchased = true;
          }

          return g;
        })
      );
    } catch (error) {
      handleError(error);
    } finally {
      closeModal();
      setIsSubmitting(false);
    }
  };

  function Countdown({
    text = "Refreshes in ",
    className = "",
  }: {
    text?: string;
    className?: string;
  }) {
    return (
      <div className={`flex ${className}`}>
        <p className="mr-2">{text}</p>
        <pre className="inline">{formattedCounter}</pre>
      </div>
    );
  }

  function FlavorText() {
    return (
      <p className="mb-4">
        These men have never stepped foot on the battlefield, let alone in the
        arena. While they might be untested and untrained, they are also cheap
        and easily replaceable. Most are slaves, criminals, or brigands, but
        that doesn't mean they can't be trained into a formidable gladiator.
      </p>
    );
  }

  function TableHeader() {
    return (
      <div className="py-2 text-lg sm:grid grid-cols-8 hidden">
        {Object.keys(gladiators[0])
          .filter((key) => key !== "id")
          .map((key) => (
            <h4
              key={key}
              className="capitalize text-center first-of-type:text-left"
            >
              {key}
            </h4>
          ))}
      </div>
    );
  }

  function PurchasedDisplay() {
    return (
      <div className="w-full h-[45px] flex justify-center items-center border-b border-gray-600">
        <h3 className="text-2xl">Purchased</h3>
      </div>
    );
  }

  function GladiatorDisplay({ gladiator }: { gladiator: MarketGladiator }) {
    return (
      <div className="py-2 text-2xl grid sm:grid-cols-8 border-b border-gray-600 grid-cols-2">
        {Object.entries(gladiator)
          .filter(([key]) => key !== "id")
          .map(([key, value]) => (
            <Fragment key={key}>
              <h4 className="capitalize sm:hidden visible">{key}</h4>
              <p className="sm:first-of-type:text-left text-ellipsis truncate text-center">
                {`${value} ${key === "price" ? "𐆖" : ""}`}
              </p>
            </Fragment>
          ))}
        <div className="col-span-2 mx-auto flex items-center">
          <PrimaryButton
            className="py-0 mb-4 sm:mb-0"
            disabled={isSubmitting}
            onClick={() => openModal(gladiator)}
          >
            Buy
          </PrimaryButton>
        </div>
      </div>
    );
  }

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <div className="w-full flex justify-between text-gray-800 dark:text-gray-200">
          <h2 className="font-semibold text-xl leading-tight">Market</h2>
          <Countdown />
        </div>
      }
    >
      <Head title="Market" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <Card className="mb-8">
            <h3 className="font-semibold text-xl leading-tight">The Dregs</h3>
            <FlavorText />
            {leftToPurchase > 0 ? (
              <>
                <TableHeader />
                {gladiators?.map((g: MarketGladiator) => {
                  return g.purchased ? (
                    <PurchasedDisplay key={g.id} />
                  ) : (
                    <GladiatorDisplay key={g.id} gladiator={g} />
                  );
                })}
              </>
            ) : (
              <>
                <p className="text-center text-xl">
                  There are no more gladiators to purchase today.
                </p>
                <div className="flex justify-center text-2xl">
                  <Countdown className="text-center" text="Come back in " />
                </div>
              </>
            )}
          </Card>
          <Modal show={showModal} onClose={closeModal}>
            {selectedGladiator && (
              <div className="m-4 text-3xl text-gray-800 dark:text-gray-200 text-center">
                <p className="mb-4">{`Purchase ${selectedGladiator.name} for ${selectedGladiator.price} 𐆖?`}</p>
                <div className="flex justify-center items-center gap-4">
                  <SecondaryButton onClick={closeModal}>Cancel</SecondaryButton>
                  <PrimaryButton onClick={() => purchase(selectedGladiator.id)}>
                    Purchase
                  </PrimaryButton>
                </div>
              </div>
            )}
          </Modal>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
